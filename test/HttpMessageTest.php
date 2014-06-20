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
use zpt\rest\message\HttpMessage;

class HttpMessageTest extends TestCase {

	public function testGetProtocolVersion() {
		$message = new HttpMessage('1.1');

		$actual = $message->getProtocolVersion();
		$this->assertEquals('1.1', $actual);
	}

	public function testGetBody() {
		$message = new HttpMessage('1.1');

		$this->assertNull($message->getBody());
	}

	public function testHasHeader() {
		$message = new HttpMessage('1.1');

		$this->assertFalse($message->hasHeader('Content-Type'));

		$message->setHeader('Content-Type', 'text/plain');
		$this->assertTrue($message->hasHeader('Content-Type'));
		$this->assertTrue($message->hasHeader('content-type'));
		$this->assertTrue($message->hasHeader('CONTENT-TYPE'));
	}

	public function testGetHeaders() {
		$message = new HttpMessage('1.1');

		$headers = $message->getHeaders();
		$this->assertInternalType('array', $headers);
		$this->assertCount(0, $headers);
	}

	public function testSetHeaders() {
		$message = new HttpMessage('1.1');

		$message->setHeaders([
			'Content-Type' => 'text/plain',
			'Content-Length' => '1024'
		]);
		$this->assertEquals('text/plain', $message->getHeader('Content-Type'));
		$this->assertEquals('text/plain', $message->getHeader('content-type'));
		$this->assertEquals('1024', $message->getHeader('Content-Length'));

		$hdrs = $message->getHeaders();
		$this->assertCount(2, $hdrs);
		$this->assertArrayHasKey('Content-Type', $hdrs);

		$message->setHeaders([
			'content-type' => 'text/html',
			'Accept' => [ 'text/html;q=0.9', 'text/plain;q=0.8', '*/*;q=0.1' ]
		]);
		$this->assertFalse($message->hasHeader('Content-Length'));
		$this->assertEquals('text/html', $message->getHeader('Content-Type'));
		$this->assertEquals(
			'text/html;q=0.9,text/plain;q=0.8,*/*;q=0.1',
			$message->getHeader('Accept'));
	}

	public function testGetHeader() {
		$message = new HttpMessage('1.1');

		$hdr = $message->getHeader('Content-Type');
		$this->assertInternalType('string', $hdr);
		$this->assertEmpty($hdr);
		$message->setHeader('Content-Type', 'text/plain');
		$this->assertNotEmpty($message->getHeader('Content-Type'));
		$this->assertNotEmpty($message->getHeader('content-type'));
		$this->assertNotEmpty($message->getHeader('CONTENT-TYPE'));

		$this->assertEquals('text/plain', $message->getHeader('Content-Type'));

		$message->setHeader('Accept', [ 'text/html;q=0.9', 'text/plain;q=0.8' ]);
		$acceptHdr = $message->getHeader('Accept');
		$this->assertEquals('text/html;q=0.9,text/plain;q=0.8', $acceptHdr);
	}

	public function testGetHeaderAsArray() {
		$message = new HttpMessage('1.1');

		$hdrAr = $message->getHeaderAsArray('Content-Type');
		$this->assertInternalType('array', $hdrAr);
		$this->assertEmpty($hdrAr);

		$message->setHeader('Content-Type', 'text/plain');
		$hdrAr = $message->getHeaderAsArray('Content-Type');
		$this->assertEquals([ 'text/plain' ], $hdrAr);

		$message->setHeader('Accept', [ 'text/html;q=0.9', 'text/plain;q=0.8' ]);
		$hdrAr = $message->getHeaderAsArray('Accept');
		$this->assertEquals([ 'text/html;q=0.9', 'text/plain;q=0.8' ], $hdrAr);
	}

	public function testSetHeader() {
		$message = new HttpMessage('1.1');

		$message->setHeader('Content-Type', 'text/plain');
		$this->assertEquals('text/plain', $message->getHeader('Content-Type'));

		$message->setHeader('content-type', 'text/html');
		$this->assertEquals('text/html', $message->getHeader('Content-Type'));
	}

	public function testAddHeader() {
		$message = new HttpMessage('1.1');

		$message->setHeader('Accept', 'text/html;q=0.9');
		$message->addHeader('Accept', 'text/plain;q=0.8');
		$acceptHdr = $message->getHeader('Accept');
		$this->assertEquals('text/html;q=0.9,text/plain;q=0.8', $acceptHdr);
	}

	public function testAddHeaders() {
		$message = new HttpMessage('1.1');

		$message->setHeaders([
			'Content-Type' => 'text/plain',
			'Content-Length' => '1024',
			'Accept' => 'text/html;q=0.9'
		]);

		$message->addHeaders([
			'Accept' => [ 'text/plain;q=0.8', '*/*;q=0.1' ],
			'Set-Cookie' => [ 'param1=value1', 'param2=value2' ]
		]);

		$expected = [
			'Content-Type' => [ 'text/plain' ],
			'Content-Length' => [ '1024' ],
			'Accept' => [ 'text/html;q=0.9', 'text/plain;q=0.8', '*/*;q=0.1' ],
			'Set-Cookie' => [ 'param1=value1', 'param2=value2' ]
		];
		$this->assertEquals($expected, $message->getHeaders());
	}

	public function testRemoveNonExistantHeader() {
		$message = new HttpMessage('1.1');

		$message->removeHeader('Content-Type');
		$this->assertFalse($message->hasHeader('Content-Type'));
	}
}
