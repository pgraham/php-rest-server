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
 * php://output stream.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class ResponseBodyOutputStream implements StreamInterface
{

	private $out;

	public function __construct() {
		$this->out = fopen('php://output', 'r+');
	}

	public function close() {
		fclose($this->out);
	}

	public function detach() {
		$this->out = null;
	}

	public function getSize() {
		return null;
	}

	public function tell() {
		return ftell($this->out);
	}

	public function eof() {
		return feof($this->out);
	}

	public function isSeekable() {
		return true;
	}

	public function seek($offset, $whence = SEEK_SET) {
		return fseek($this->out, $offset, $whence);
	}

	public function isWritable() {
		return true;
	}

	public function write($string) {
		fwrite($this->out, $string);
	}

	public function isReadable() {
		return true;
	}

	public function read($length) {
		return fread($this->out, $length);
	}

	public function getContents($maxLength = -1) {
		return stream_get_contents($this->out, $maxLength);
	}
}
