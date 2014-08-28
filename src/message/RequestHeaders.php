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
 * This class encapsulates the request headers for the current request in a web
 * context.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class RequestHeaders
{

	public function get() {
		if (function_exists('getallheaders')) {
			return (new HeaderParser())->parse(getallheaders());
		} else {
			return [];
		}
	}

}
