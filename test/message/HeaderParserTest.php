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
use zpt\rest\message\HeaderParser;

class HeaderParserTest extends TestCase
{

	public function testParseSingleValues() {
		$headers = [
			'Content-Type' => 'text/plain'
		];

		$parsed = (new HeaderParser())->parse($headers);

		$this->assertArrayHasKey('Content-Type', $parsed);

		$this->assertCount(1, $parsed['Content-Type']);
		$this->assertEquals('text/plain', $parsed['Content-Type'][0]);

	}

	public function testParseMultipleValues() {
		$headers = [
			'Accept' => 'text/html;q=0.9,text/plain;q=0.8,*/*;q=0.1'
		];

		$parsed = (new HeaderParser())->parse($headers);

		$this->assertArrayHasKey('Accept', $parsed);
		$acceptHdr = $parsed['Accept'];

		$this->assertCount(3, $acceptHdr);
		$this->assertEquals('text/html;q=0.9', $acceptHdr[0]);
		$this->assertEquals('text/plain;q=0.8', $acceptHdr[1]);
		$this->assertEquals('*/*;q=0.1', $acceptHdr[2]);
	}
}
