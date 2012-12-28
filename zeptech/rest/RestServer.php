<?php
/**
 * =============================================================================
 * Copyright (c) 2010, Philip Graham
 * All rights reserved.
 *
 * This file is part of php-rest-server and is licensed by the Copyright holder
 * under the 3-clause BSD License.  The full text of the license can be found in
 * the LICENSE.txt file included in the root directory of this distribution or
 * at the link below.
 * =============================================================================
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace zeptech\rest;

// TODO Allow an explicit response type be set in the response so that the
//      dependency to oboe can be removed.
use \oboe\Page;
use \Exception;

/**
 * This class encapsulates the process of handling a RESTful request for a
 * resource.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class RestServer {

  /*
   * The declared formats for the response which will be recognized by the
   * requesting agent.
   */
  private $_acceptTypes = array('application/json');

  /* Default exception handler. */
  private $defaultExceptionHandler;

  /* Registered exception handlers.  */
  private $exceptionHandlers = array();

  /*
   * Map of URI's and their handlers that are recognized by this server.
   */
  private $_mappings = array();

  /*
   * Object which encapsulates the request being handled by the server.
   * This will not be populated until {@link #handleRequest()} is called.
   */
  private $_request;

  /*
   * Object which encapsulates the response to send to the requesting agent.
   * This will not be populated until {@link #handleRequest()} is called.
   */
  private $_response;

  public function __construct() {
    $this->defaultExceptionHandler = new DefaultExceptionHandler();
  }

  /**
   * Add a resource mapping for a resource URI handled by this server.  More
   * specific mappings need to be added first.
   *
   * @param string $uriTemplate URI template for URI to associate to the given
   *   RequestHandler.
   * @param RequestHandler $handler
   * @param string $id Optional id for the mapping.  This allows a handler for
   *   more than one URI template to quickly determine the type of request
   */
  public function addMapping($uriTemplate, RequestHandler $handler, $id = null,
      $method = null)
  {
    $this->_mappings[] = new UriMapping($uriTemplate, $handler, $id, $method);
  }

  /**
   * Getter for the body text of the response.
   *
   * @return string
   */
  public function getResponse() {
    if ($this->_response === null) {
      return '';
    }

    if ($this->_response->getType() !== null) {
      $type = $this->_response->getType();
      switch ($type) {
        case 'application/pdf':
        $encoder = EncoderFactory::getInstance()->getBinaryEncoder();
        break;

        default:
        $encoder = EncoderFactory::getInstance()->getTextEncoder();
        break;
      }
    } else {
      // For each of accepted types specified in the request, attempt to find
      // an appropriate encoder.
      $encoder = null;
      foreach ($this->_acceptTypes AS $acceptType) {
        if ( ((string) $acceptType) === '*/*' ) {
          // Attempt to determine an encoder based on the type of the response
          $data = $this->_response->getData();

          if (is_object($data) || is_array($data)) {
            if ($data instanceof Page) {
              $encoder = EncoderFactory::getInstance()->getHtmlEncoder();
            } else {
              $encoder = EncoderFactory::getInstance()->getJsonEncoder();
            }
          } else {
            $encoder = EncoderFactory::getInstance()->getTextEncoder();
          }
        } else {
          $encoder = EncoderFactory::getEncoder($acceptType);
          if ($encoder !== null) {
            break;
          }
        }
      }
    }

    
    if ($encoder === null) {
      // No supported type was found. According the HTTP/1.1 spec a
      // '406 Not Acceptable' header can be returned or:
      //
      //   "... HTTP/1.1 servers are allowed to return responses which are
      //    not acceptable according to the accept headers sent in the
      //    request. In some cases, this may even be preferable to sending a
      //    406 response. User agents are encouraged to inspect the headers of
      //    an incoming response to determine if it is acceptable."
      //
      // So instead of returning a 406 Not Acceptable, a TextEncoder is used to
      // return a text/plain response
      $encoder = new TextEncoder();
    }
    return $encoder->encode($this->_response);
  }

  /**
   * Getter for any headers to send with the response.
   *
   * @return array
   */
  public function getResponseHeaders() {
    if ($this->_response === null) {
      return array();
    }
    return $this->_response->getHeaders();
  }

  /**
   * Handle a resource request.
   *
   * @param string $action The requested action to perform on the resource
   *   specified by the given URI.
   * @param string $uri
   */
  public function handleRequest($action, $uri) {
    try {
      if ($uri !== '/') {
        $uri = rtrim($uri, '/');
      }

      $action = strtoupper($action);

      $handler = null;
      $parameters = null;
      $mappingId = null;
      foreach ($this->_mappings AS $mapping) {
        $matches = null;
        if ($mapping->getTemplate()->matches($uri, $matches)) {
          $mappingMethod = $mapping->getMethod();
          if ($mappingMethod === null || $mappingMethod === $action) {
            $handler = $mapping->getHandler();
            $parameters = $matches;
            $mappingId = $mapping->getId();
            break;
          }
        }
      }

      $this->_response = new Response();
      if ($handler === null) {
        throw new RestException(404);
      }

      $this->_request = new Request($uri, $mappingId);
      $this->_request->setAction($action);
      if ($parameters !== null) {
        $this->_request->setParameters($parameters);
      }

      switch ($action) {
        case 'DELETE':
        $handler->delete($this->_request, $this->_response);
        break;

        case 'GET':
        $this->_request->setQuery($this->_parseGet());
        $handler->get($this->_request, $this->_response);
        break;

        case 'POST':
        $this->_request->setData($this->_parsePost());
        $handler->post($this->_request, $this->_response);
        break;

        case 'PUT':
        $this->_request->setData($this->_parsePut());
        $handler->put($this->_request, $this->_response);
        break;
      }
    } catch (RestException $e) {
      $this->_response->clearHeaders();

      $hdr = "HTTP/1.1 {$e->getCode()} {$e->getHeaderMessage()}";
      $this->_response->header($hdr);
      foreach ($e->getHeaders() as $hdr) {
        $this->_response->header($hdr);
      }
      $this->_response->setData($e->getMessage());

    } catch (Exception $e) {
      // TODO $this->logger->log($e);

      $type = get_class($e);
      if (array_key_exists($type, $this->exceptionHandlers)) {
        $handler = $this->exceptionHandlers[$type];
      } else {
        $handler = $this->defaultExceptionHandler;
      }

      $handler->handleException($e, $this->_request, $this->_response);
    }
  }

  /**
   * Register an exception handler for the specified type of exception.
   *
   * @param string $type
   * @param ExceptionHandler $handler
   */
  public function registerExceptionHandler($type, ExceptionHandler $handler) {
    $this->exceptionHandlers[$type] = $handler;
  }

  /**
   * Set the response formats accepted by the requesting agents.
   *
   * @param string $accept HTTP Accept header
   */
  public function setAcceptType($accept) {
    // HTTP/1.1 Accept Header Definition:
    // ----------------------------------
    //
    // Accept         = "Accept" ":"
    //                  #( media-range [ accept-params ] )
    //
    // media-range    = ( "*/*"
    //                  | ( type "/" "*" )
    //                  | ( type "/" subtype )
    //                  ) *( ";" parameter )
    // accept-params  = ";" "q" "=" qvalue *( accept-extension )
    // accept-extension = ";" token [ "=" ( token | quoted-string ) ]

    // $this->_acceptTypes = explode(', ', $accept);

    if (preg_match_all('/(\*|[^\/,;=\s]+)\/(\*|[^\/,;=\s]+)(?:;\s*q\s*=\s*(1|0\.\d+))?,?/',
        $accept, $matches, PREG_SET_ORDER))
    {
      $this->_acceptTypes = array();
      foreach ($matches AS $match) {
        $accept = new AcceptType($match[1], $match[2]);
        if (isset($match[3])) {
          $accept->setQValue((float) $match[3]);
        }

        $this->_acceptTypes[] = $accept;
      }

      usort($this->_acceptTypes, function ($a, $b) {
        $aq = $a->getQValue();
        $bq = $b->getQValue();

        // If q-values are equal, preserve their order
        if ($aq == $bq) {
          return 1;
        }

        // We are sorting by descending q-value
        if ($aq > $bq) {
          return -1;
        } else {
          return 1;
        }
      });

    }
  }

  private function _decodeData($data) {
    // If Content-Type is not specified by the client then no decoding is
    // possible
    if (!isset($_SERVER['CONTENT_TYPE'])) {
      return $data;
    }

    $contentType = $_SERVER['CONTENT_TYPE'];
    if (strpos($contentType, 'application/json') !== false) {
      return json_decode($data);
    }

    // Content-Type is not supported for automatic decoding, return the raw data
    return $data;
  }

  private function _parseGet() {
    return $_GET;
  }

  private function _parsePost() {
    if (!empty($_POST)) {
      return $_POST;
    }

    global $HTTP_RAW_POST_DATA;
    if (!empty($HTTP_RAW_POST_DATA)) {
      $postData = $HTTP_RAW_POST_DATA;
    } else {
      $postData = file_get_contents('php://input', 'r');
    }

    return $this->_decodeData($postData);
  }

  private function _parsePut() {
    $putData = file_get_contents('php://input', 'r');
    return $this->_decodeData($putData);
  }

}
