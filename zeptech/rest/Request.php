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
 * This class encapsulates data about a resource request.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Request {

  /* The HTTP method of the request. */
  private $action;

  /* Data sent with the request. */
  private $_data;

  /* The id of the mapping that handles this request. */
  private $_mappingId;

  /* Values for any parameters specified in the mapping's URI template */
  private $_parameters;

  /* Query parameters sent with the request. */
  private $_query;

  /* The requested URI */
  private $_uri;

  public function __construct($uri, $mappingId = null) {
    $this->_uri = $uri;
    $this->_mappingId = $mappingId;
  }

  /**
   * Getter for the request's HTTP method.
   *
   * @return string
   */
  public function getAction() {
    return $this->action;
  }

  /**
   * Getter for the request data.
   *
   * @return array
   */
  public function getData($key = null) {
    if ($key !== null && is_array($this->_data)) {
      return $this->_data[$key];
    }
    return $this->_data;
  }

  /**
   * Getter for the mapping that is handling the request.
   *
   * @return string
   */
  public function getMappingId() {
    return $this->_mappingId;
  }

  /**
   * Getter for the parameter with the given name.
   *
   * @return mixed
   */
  public function getParameter($parameter) {
    if (isset($this->_parameters[$parameter])) {
      return $this->_parameters[$parameter];
    }
    return null;
  }

  /**
   * Getter for the expanded values of the request's matching URI template.
   *
   * @return array
   */
  public function getParameters() {
    return $this->_parameters;
  }

  /**
   * Getter for the request's query parameters.
   *
   * @return array
   */
  public function getQuery() {
    return $this->_query;
  }

  /**
   * Getter for the request's query parameter with the given name.
   *
   * @param string $name The name of the query parameter to fetch.
   * @return string
   */
  public function getQueryParameter($key) {
    if (isset($this->_query[$key])) {
      return $this->_query[$key];
    }
    return null;
  }

  /**
   * Getter for the request's URI.
   *
   * @return string
   */
  public function getUri() {
    return $this->_uri;
  }

  /**
   * Setter for the request's HTTP method.
   *
   * @param string $action
   */
  public function setAction($action) {
    $this->action = $action;
  }

  /**
   * Setter for any raw data sent with the request.  This is typically the
   * request body, preprocessed by PHP.
   *
   * @param array $data
   */
  public function setData($data) {
    $this->_data = $data;
  }

  /**
   * Setter for any expanded values for the URI template that matches this
   * request.
   *
   * @param array $parameters
   */
  public function setParameters(array $parameters = null) {
    $this->_parameters = $parameters;
  }

  /**
   * Setter for any query parameters sent with the request.
   *
   * @param array $query
   */
  public function setQuery(array $query = null) {
    $this->_query = $query;
  }

}
