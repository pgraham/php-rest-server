<?php
/*
 * Copyright (c) 2014, Philip Graham
 * All rights reserved.
 *
 * This file is part of php-rest-server. For the full copyright and license
 * information please view the LICENSE file that was distributed with this
 * source code.
 */
namespace zpt\rest\uri;

/**
 * This class encapsulates the URL used for the current request. The URL is
 * created from information available in the $_SERVER super global.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class RequestUrl
{

	private $scheme;
	private $host;
	private $post;
	private $path;
	private $query;

	private $url;

	public function __construct() {
		$ssl = isHttps();
		$this->scheme = $ssl ? 'https' : 'http';

		$this->host = $_SERVER['SERVER_NAME'];
		$this->port = $_SERVER['SERVER_PORT'];

		if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {
			$uriParts = explode('?', $_SERVER['REQUEST_URI']);
			$this->path = $uriParts[0];
			$this->query = $uriParts[1];
		} else {
			$this->path = $_SERVER['REQUEST_URI'];
		}

		if (
			($ssl && $this->port != 443) ||
			(!$ssl && $this->port != 80)
		) {
			$authority = "$this->host:$this->port";
		} else {
			$authority = $this->host;
		}

		$this->url = "$this->scheme://$authority$this->path";
		if ($this->query) {
			$this->url .= "?$this->query";
		}
	}

	public function get() {
		return $this->url;
	}
}
