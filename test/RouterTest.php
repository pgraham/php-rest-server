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
use zpt\rest\Router;

class RouterTest extends TestCase {

	public function testGetIndex() {
		$app = new Router();

		$processed = false;
		$app->get('/', function ($req, $res) use (&$processed) {
			$processed = true;
		});

		$app->process('GET', '/');

		$this->assertTrue($processed);
	}

	public function testGetIndexMultipleRoutes() {
		$app = new Router();

		$processedIndex = false;
		$app->get('/', function ($req, $res) use (&$processedIndex) {
			$processedIndex = true;
		});

		$processedHome = false;
		$app->get('/home.html', function ($req, $res) use (&$processedHome) {
			$processedHome = true;
		});

		$app->process('GET', '/');

		$this->assertTrue($processedIndex);
		$this->assertFalse($processedHome);
	}

	public function testGetIndexMultipleMethods() {
		$app = new Router();

		$processedGet = false;
		$app->get('/', function ($req, $res) use (&$processedGet) {
			$processedGet = true;
		});

		$processedPost = false;
		$app->post('/', function ($req, $res) use (&$processedPost) {
			$processedPost = true;
		});

		$app->process('GET', '/');

		$this->assertTrue($processedGet);
		$this->assertFalse($processedPost);
	}

	public function testGetUriTemplate() {
		$app = new Router();

		$processedTimes = 0;
		$app->get('/collection/{id}', function ($req, $res) use (&$processedTimes) {
			$processedTimes++;
		});

		for ($i = 0; $i < 10; $i++) {
			$app->process('GET', "/collection/$i");
		}

		$app->process('GET', '/collection');

		$this->assertEquals(10, $processedTimes);
	}

}
