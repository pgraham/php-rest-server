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

/**
 * This class parses raw header values into the format expected by
 * {@link Psr\HttpMessage\MessageInterface}.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class HeaderParser
{

	/**
	 * Parse the given list of header values into string arrays.
	 */
	public function parse(array $headers) {
		$parsed = [];

		foreach ($headers as $header => $value) {
			if (strcasecmp($header, 'set-cookie') === 0) {
				$parsed[$header] = explode(';', $value);
			} else {
				$parsed[$header] = explode(',', $value);
			}
		}

		return $parsed;
	}
}
