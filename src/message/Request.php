<?php
/*
 * Copyright (c) 2014, Philip Graham
 * All rights reserved.
 *
 * This file is part of php-rest-server. For the full copyright and license
 * information please view the LICENSE file that was distributed with this
 * source code.
 */
namespace zpt\rest\message;

use Psr\HttpMessage\RequestInterface;
use InvalidArgumentException;
use StdClass;

/**
 * This class encapsulates data about a resource request.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Request extends HttpMessage implements RequestInterface
{

	/* The HTTP method of the request. */
	private $method;

	/* Data sent with the request. */
	private $data;

	/* Values for any parameters specified in the mapping's URI template */
	private $parameters;

	/* Query parameters sent with the request. */
	private $query;

	/* The requested URI */
	private $url;

	/**
	 * Getter for the request's HTTP method.
	 *
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * Setter for the request's HTTP method.
	 *
	 * @param string $method
	 */
	public function setMethod($method) {
		$this->method = $method;
	}

	/**
	 * Getter for the request's URL.
	 *
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Setter for the request's URL.
	 *
	 * @param string $url
	 */
	public function setUrl($url) {
		$urlInfo = parse_url($url, PHP_URL_SCHEME);
		if (!in_array($urlInfo, [ 'http', 'https' ])) {
			throw new InvalidArgumentException("URL is not HTTP: $url");
		}

		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			throw new InvalidArgumentException("Not a valid URL: $url");
		}

		$this->url = $url;
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
