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

/**
 * This class encapsulates a URI template for a relative reference. URI should 
 * be relative to the scheme and authority of the server for which routes are 
 * being defined.
 *
 * ## Examples
 *
 *  -  /
 *  -  /entity
 *  -  /collection
 *  -  /collection/{id}
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class UriTemplate {

	private $tmpl;
	private $matchRegex;
	private $paramNames = array();

	/**
	 * Create a new UriTemplate object. The specified UriTemplate should only 
	 * contain the path portion of the URI and should not contain the
	 * scheme://host:port portion of the URI.
	 *
	 * @param string $uriTemplate
	 */
	public function __construct($uriTemplate) {
		$this->tmpl = $uriTemplate;

		if ($this->tmpl === '/') {
			$this->matchRegex = '/^\/$/';
			return;
		}

		// Parse out the names of any expressions contained in the template
		if(preg_match_all('/\/\{([^}]+)\}/', $this->tmpl, $matches)) {
			$this->paramNames = $matches[1];
		}

		// Build a regular expression used to match concrete URIs against the 
		// template.
		$tmplCmps = explode('/', ltrim($this->tmpl, '/'));
		$escaped = array();
		foreach ($tmplCmps as $cmp) {
			if (preg_match('/\{.+\}/', $cmp)) {
				$escaped[] = '([^\/]+)';
			} else {
				$escaped[] = preg_quote($cmp);
			}
		}
		$this->matchRegex = "/^\/" . implode('\/', $escaped) . "$/";
	}

	/**
	 * Expand the template using the given set of values.
	 *
	 * TODO
	 */
	//public function expand(array $values) {}

	/**
	 * Determine if a given URI is an expansion of the encapsulated template.
	 * If the template contains expressions, the expansion values of each 
	 * expression in the given URI will be used to populate the given array.
	 *
	 * @param string $uri
	 * @param &array $params
	 */
	public function matches($uri, &$params = false) {
		if (!$params) {
			$params = [];
		}

		if (preg_match($this->matchRegex, $uri, $matches)) {
			array_shift($matches);

			$params = array();
			foreach ($this->paramNames AS $idx => $param) {
				$params[$param] = $matches[$idx];
			}
			return true;
		}
		return false;
	}
}
