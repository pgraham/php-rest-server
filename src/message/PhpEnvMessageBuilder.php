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

use zpt\rest\uri\RequestUrl;
ensureFn('isHttps');

/**
 * {@link MessageBuilder implementation} that builds {@link HttpMessage}
 * instances using data available in the current PHP runtime environment.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class PhpEnvMessageBuilder implements MessageBuilder
{

	private $protocolVersion;
	private $method;
	private $requestUrl;
	private $headers;
	private $body;

	public function __construct() {
		$this->protocolVersion = $this->parseProtocolVersion();
		$this->method = $_SERVER['REQUEST_METHOD'];
		$this->requestUrl = new RequestUrl();
		$this->headers = new RequestHeaders();
		$this->body = new RequestBodyInputStream();
	}

	public function getRequest() {
		$request = new Request($this->protocolVersion);
		$request->setMethod($this->method);
		$request->setUrl($this->requestUrl->get());
		$request->setHeaders($this->headers->get());
		$request->setBody($this->body);

		return $request;
	}

	public function getResponse() {
		// TODO Implement
	}

	/*
	 * ===========================================================================
	 * Helpers
	 * ===========================================================================
	 */

	private function parseProtocolVersion() {
		$protocolVersion = String($_SERVER['SERVER_PROTOCOL']);
		return (string) $protocolVersion->stripStart('HTTP/');
	}

}
