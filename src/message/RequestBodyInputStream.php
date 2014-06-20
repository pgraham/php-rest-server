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

use Psr\HttpMessage\StreamInterface;

/**
 * {@link Psr\HttpMessage\StreamInterface} implementation that wraps the
 * php://input stream.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class RequestBodyInputStream implements StreamInterface
{

	private $in;

	public function __construct() {
		$this->in = fopen('php://input', 'r');
	}

	public function __toString() {
		return file_get_contents('php://input');
	}

	public function close() {
		fclose($this->in);
	}

	public function detach() {
		//$rsrc = $this->in;
		$this->in = null;
		//return $rsrc;
	}

	public function getSize() {
		return null;
	}

	public function tell() {
		return ftell($this->in);
	}

	public function eof() {
		return feof($this->in);
	}

	public function isSeekable() {
		return true;
	}

	public function seek($offset, $whence = SEEK_SET) {
		return fseek($this->in, $offset, $whence);
	}

	public function isWritable() {
		return false;
	}

	public function write($string) {
		// This is a read-only stream.
		return false;
	}

	public function isReadable() {
		return true;
	}

	public function read($length) {
		return fread($this->in, $length);
	}

	public function getContents($maxLength = -1) {
		return stream_get_contents($this->in, $maxLength);
	}
}
