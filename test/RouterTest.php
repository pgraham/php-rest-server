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
use zpt\rest\message\Request;
use zpt\rest\Router;

class RouterTest extends TestCase {

	private $app;
	private $msgBldr;

	protected function setUp() {
		$this->app = new Router();
		$this->msgBldr = $this->getMock('zpt\rest\message\MessageBuilder');

		$this->app->setMessageBuilder($this->msgBldr);
	}

	public function testGetIndex() {
		$this->msgBldr->expects($this->any())
		              ->method('getRequest')
		              ->will($this->returnValue(new Request('1.1')));

		$processed = false;
		$this->app->get('/', function ($req, $res) use (&$processed) {
			$processed = true;
		});

		$this->app->process('GET', '/');

		$this->assertTrue($processed);
	}

	public function testGetIndexMultipleRoutes() {
		$this->msgBldr->expects($this->any())
		              ->method('getRequest')
		              ->will($this->returnValue(new Request('1.1')));

		$processedIndex = false;
		$this->app->get('/', function ($req, $res) use (&$processedIndex) {
			$processedIndex = true;
		});

		$processedHome = false;
		$this->app->get('/home.html', function ($req, $res) use (&$processedHome) {
			$processedHome = true;
		});

		$this->app->process('GET', '/');

		$this->assertTrue($processedIndex);
		$this->assertFalse($processedHome);
	}

	public function testGetIndexMultipleMethods() {
		$this->msgBldr->expects($this->any())
		              ->method('getRequest')
		              ->will($this->returnValue(new Request('1.1')));

		$processedGet = false;
		$this->app->get('/', function ($req, $res) use (&$processedGet) {
			$processedGet = true;
		});

		$processedPost = false;
		$this->app->post('/', function ($req, $res) use (&$processedPost) {
			$processedPost = true;
		});

		$this->app->process('GET', '/');

		$this->assertTrue($processedGet);
		$this->assertFalse($processedPost);
	}

	public function testGetUriTemplate() {
		$this->msgBldr->expects($this->any())
		              ->method('getRequest')
		              ->will($this->returnValue(new Request('1.1')));

		$processedTimes = 0;
		$this->app->get('/collection/{id}', function ($req, $res) use (&$processedTimes) {
			$processedTimes++;
		});

		for ($i = 0; $i < 10; $i++) {
			$this->app->process('GET', "/collection/$i");
		}

		$this->app->process('GET', '/collection');

		$this->assertEquals(10, $processedTimes);
	}

}
