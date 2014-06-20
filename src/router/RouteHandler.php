<?php
/*
 * Copyright (c) 2014, Philip Graham
 * All rights reserved.
 *
 * This file is part of php-rest-server. For the full copyright and license
 * information please view the LICENSE file that was distributed with this
 * source code.
 */
namespace zpt\rest\router;

use zpt\rest\UriTemplate;

/**
 * This class encapsulates a URI template -> processing function mapping.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class RouteHandler {

	private $uri;
	private $handler;

	public function __construct($uri, $handler) {
		$this->uri = new UriTemplate($uri);
		$this->handler = $handler;
	}

	public function getUri() {
		return $this->uri;
	}

	public function invoke($req, $res) {
		$fn = $this->handler;
		$fn($req, $res);
	}
}
