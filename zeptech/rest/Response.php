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

/**
 * This class encapsulates data about a response to a resource request.
 *
 * TODO Separate specification of status header from other headers.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Response {

  /* Data the comprises the request body. */
  private $_data;

  /* Headers to send with the response. */
  private $_headers;

  /* The type of content in the response */
  private $_type;

  /**
   * Initialize a new Response object.
   */
  public function __construct() {
    $this->clearHeaders();
  }

  /**
   * Clear all response headers.
   */
  public function clearHeaders() {
    $this->_headers = array();
  }

  /**
   * Getter for the response's raw unencoded data.
   *
   * @return mixed
   */
  public function getData() {
    return $this->_data;
  }

  /**
   * Getter for the response's headers.
   *
   * @return string[]
   */
  public function getHeaders() {
    return $this->_headers;
  }

  /**
   * Getter for the type of data contained in the response.
   *
   * @return string
   */
  public function getType() {
    return $this->_type;
  }

  /**
   * Add a header to send with the response.
   *
   * @param string $header
   */
  public function header($header) {
    if (preg_match('/Content-Type\: (.+)/i', $header, $matches)) {
      $this->setType($matches[1]);
    }
    $this->_headers[] = $header;
  }

  /**
   * Setter for the response's raw unencoded data.
   *
   * @param mixed $data
   */
  public function setData($data = null) {
    $this->_data = $data;
  }

  /**
   * Setter for the type of content in the response.
   *
   * @param string $type
   */
  public function setType($type) {
    $this->_type = $type;
  }

}
