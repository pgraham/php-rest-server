<?php
/*
 * Copyright (c) 2014, Philip Graham
 * All rights reserved.
 *
 * This file is part of php-rest-server. For the full copyright and license
 * information please view the LICENSE file that was distributed with this
 * source code.
 */
namespace zpt\rest;

use StdClass;

/**
 * This class encapsulates data about a resource request.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Request {

	/* The HTTP method of the request. */
	private $action;

	/* Data sent with the request. */
	private $data;

	/* The id of the mapping that handles this request. */
	// This is a code smell, this is the responsibility of the Router or RouteHandler
	private $mappingId;

	/* Values for any parameters specified in the mapping's URI template */
	private $parameters;

	/* Query parameters sent with the request. */
	private $query;

	/* The requested URI */
	private $url;

	public function __construct($url, $mappingId = null) {
		$this->url = $url;
		$this->mappingId = $mappingId;
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
		if ($key !== null) {
			if ($this->data instanceof StdClass) {
				return $this->data->$key;
			} else if (is_array($this->data)) {
				return $this->data[$key];
			} else {
				return null;
			}
		}
		return $this->data;
	}

	/**
	 * Getter for the mapping that is handling the request.
	 *
	 * @return string
	 */
	public function getMappingId() {
		return $this->mappingId;
	}

	/**
	 * Getter for the parameter with the given name.
	 *
	 * @return mixed
	 */
	public function getParameter($parameter) {
		if (isset($this->parameters[$parameter])) {
			return $this->parameters[$parameter];
		}
		return null;
	}

	/**
	 * Getter for the expanded values of the request's matching URI template.
	 *
	 * @return array
	 */
	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * Getter for the request's query parameters.
	 *
	 * @return array
	 */
	public function getQuery() {
		return $this->query;
	}

	/**
	 * Getter for the request's query parameter with the given name.
	 *
	 * @param string $name The name of the query parameter to fetch.
	 * @return string
	 */
	public function getQueryParameter($key) {
		if (isset($this->query[$key])) {
			return $this->query[$key];
		}
		return null;
	}

	/**
	 * Getter for the request's URI.
	 *
	 * @return string
	 */
	public function getUri() {
		return $this->url;
	}

	/**
	 * Indicate whether or not the specified data value is present in the
	 * request.
	 *
	 * @param string $key
	 * @return boolean
	 */
	public function hasData($key) {
		if ($this->data instanceof StdClass) {
			return isset($this->data->$key);
		} else if (is_array($this->data)) {
			return isset($this->data[$key]);
		} else {
			return false;
		}
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
		$this->data = $data;
	}

	/**
	 * Setter for any expanded values for the URI template that matches this
	 * request.
	 *
	 * @param array $parameters
	 */
	public function setParameters(array $parameters = null) {
		$this->parameters = $parameters;
	}

	/**
	 * Setter for any query parameters sent with the request.
	 *
	 * @param array $query
	 */
	public function setQuery(array $query = null) {
		$this->query = $query;
	}

}
