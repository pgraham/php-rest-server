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

use Psr\HttpMessage\ResponseInterface;

/**
 * This class encapsulates data about a response to a resource request.
 *
 * TODO Separate specification of status header from other headers.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Response extends HttpMessage implements ResponseInterface
{

	private $status;

	/**
	 * Initialize a new Response object.
	 */
	public function __construct($protocolVersion, $statusCode = '200') {
		parent::__construct($protocolVersion);
		$this->status = new HttpStatus($statusCode);
	}

	public function getStatusCode() {
		return $this->status->getCode();
	}

	public function getReasonPhrase() {
		return $this->status->getReasonPhrase();
	}

}
