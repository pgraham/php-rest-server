<?php
/*
 * Copyright (c) 2014, Philip Graham
 * All rights reserved.
 *
 * This file is part of php-rest-server. For the full copyright and license
 * information please view the LICENSE file that was distributed with this
 * source code.
 */
namespace zpt\rest\test;

require_once __DIR__ . '/test-setup.php';

use PHPUnit_Framework_TestCase as TestCase;
use zpt\rest\Request;

class RequestTest extends TestCase {

	public function testGetAndSetMethod() {
		$request = new Request('1.1');
		$request->setMethod('GET');

		$actual = $request->getMethod();
		$this->assertEquals('GET', $actual);
	}

	public function testGetAndSetUrl() {
		$request = new Request('1.1');
		$request->setUrl('http://www.example.com/');

		$actual = $request->getUrl();
		$this->assertEquals('http://www.example.com/', $actual);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetUrlInvalidScheme() {
		$request = new Request('1.1');
		$request->setUrl('mailto:someone@example.com');
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testSetInvalidUrl() {
		$request = new Request('1.1');
		$request->setUrl('http:///index.html');
	}

}

