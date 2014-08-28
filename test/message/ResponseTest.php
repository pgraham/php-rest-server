<?php
/*
 * Copyright (c) 2014, Philip Graham
 * All rights reserved.
 *
 * This file is part of php-rest-server. For the full copyright and license
 * information please view the LICENSE file that was distributed with this
 * source code.
 */
namespace zpt\rest\test\message;

require_once __DIR__ . '/../test-setup.php';

use PHPUnit_Framework_TestCase as TestCase;
use zpt\rest\message\Response;

/**
 * This class test the {@link Response} class.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class ResponseTest extends TestCase
{

	public function test200() {
		$response = new Response('1.1');

		$this->assertEquals('200', $response->getStatusCode());
		$this->assertEquals('OK', $response->getReasonPhrase());
	}

	public function testNumeric() {
		$response = new Response('1.1', 200);

		$this->assertEquals('200', $response->getStatusCode());
		$this->assertEquals('OK', $response->getReasonPhrase());
	}
}
