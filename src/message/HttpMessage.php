<?php
/*
 * Copyright (c) 2014, Philip Graham
 * All rights reserved.
 *
 * This file is part of php-rest-server. For the full copyright and license
 * information please view the LICENSE file that was distributed with this
 * source code.
 */
namespace zpt\rest\messsage;

use Psr\HttpMessage\MessageInterface;
use Psr\HttpMessage\StreamInterface;

/**
 * Base class for HttpMessage implementations. There are two types of messages:
 * {@link Request} and {@link Response}.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class HttpMessage implements MessageInterface
{

	private $protocolVersion;
	private $headers = [];
	private $body;

	public function __construct($protocolVersion) {
		$this->protocolVersion = $protocolVersion;
	}

	public function getProtocolVersion() {
		return $this->protocolVersion;
	}

	public function getBody() {
		return $this->body;
	}

	public function setBody(StreamInterface $body = null) {
		$this->body = $body;

		return $this;
	}

	public function getHeaders() {
		$headers = [];

		foreach ($this->headers as $header => $value) {
			$header = $this->formatHeader($header);
			$headers[$header] = $value;
		}
		return $headers;
	}

	public function hasHeader($header) {
		$header = $this->normalizeHeader($header);
		return isset($this->headers[$header]);
	}

	public function getHeader($header) {
		$header = $this->normalizeHeader($header);

		if (isset($this->headers[$header])) {
			return implode(',', $this->headers[$header]);
		} else {
			return "";
		}
	}

	public function getHeaderAsArray($header) {
		$header = $this->normalizeHeader($header);

		if (isset($this->headers[$header])) {
			return $this->headers[$header];
		} else {
			return [];
		}
	}

	public function setHeader($header, $value) {
		$header = $this->normalizeHeader($header);
		$value = $this->normalizeValue($value);

		$this->headers[$header] = $value;

		return $this;
	}

	public function setHeaders(array $headers) {
		$this->headers = [];

		foreach ($headers as $header => $value) {
			$header = $this->normalizeHeader($header);
			$value = $this->normalizeValue($value);
			$this->headers[$header] = $value;
		}

		return $this;
	}

	public function addHeader($header, $value) {
		$header = $this->normalizeHeader($header);
		if (!isset($this->headers[$header])) {
			$this->headers[$header] = [];
		}
		$this->headers[$header][] = $value;

		return $this;
	}

	public function addHeaders(array $headers) {
		foreach ($headers as $header => $value) {
			$header = $this->normalizeHeader($header);
			$value = $this->normalizeValue($value);

			if (!$this->hasHeader($header)) {
				$this->headers[$header] = $value;
			} else {
				$this->headers[$header] = array_merge($this->headers[$header], $value);
			}
		}

		return $this;
	}

	public function removeHeader($header) {
		$header = $this->normalizeHeader($header);
		unset($this->headers[$header]);
	}

	private function formatHeader($header) {
		$fmtd = [];
		$parts = explode('-', $header);
		foreach ($parts as $part) {
			$fmtd[] = ucfirst($part);
		}
		return implode('-', $fmtd);
	}

	private function normalizeHeader($header) {
		return strtolower($header);
	}

	private function normalizeValue($value) {
		if (!is_array($value)) {
			$value = [ $value ];
		}
		return $value;
	}
}
