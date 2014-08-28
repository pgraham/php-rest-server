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
 * Interface for Factory objects that build construct {@link HttpMessage}
 * objects.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
interface MessageBuilder
{

	/**
	 * Construct a {@link Request} message.
	 *
	 * @return Request
	 */
	public function getRequest();

	/**
	 * Construct a {@link Response} message.
	 *
	 * @return Response
	 */
	public function getResponse();

}
