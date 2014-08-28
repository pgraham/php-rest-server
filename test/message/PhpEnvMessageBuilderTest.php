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
use zpt\rest\message\PhpEnvMessageBuilder;

class PhpEnvMessageBuilderTest extends TestCase
{

	protected function setUp() {
		// Mock $_SERVER variables required by PhpEnvRequestBuilder dependencies.
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['SERVER_NAME'] = 'localhost';
		$_SERVER['SERVER_PORT'] = '80';
		$_SERVER['REQUEST_URI'] = '/index.html';
	}

	public function testParseHttpProtocolVersion() {
		$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';

		$builder = new PhpEnvMessageBuilder();
		$request = $builder->getRequest();

		$this->assertEquals('1.1', $request->getProtocolVersion());
	}

}
