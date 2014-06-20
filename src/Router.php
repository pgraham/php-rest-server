<?php
/*
 * Copyright (c) 2014, Philip Graham
 * All rights reserved.
 *
 * This file is part of php-rest-server. For the full copyright and license
 * information please view the LICENSE file that was distributed with this
 * source code.
 */
namespace zpt\rest;

use zpt\rest\message\MessageBuilder;
use zpt\rest\message\PhpEnvMessageBuilder;
use zpt\rest\message\Request;
use zpt\rest\router\RouteHandler;

/**
 * This class encapsulates the Router interface for defining serviced URIs.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class Router {

	private $handlers = [
		'GET' => [],
		'POST' => [],
		'PUT' => [],
		'DELETE' => []
	];

	private $messageBuilder;

	/**
	 * Add a GET request handler for the specified URI template.
	 *
	 * @param string $uri
	 *   The URI Template for requests to which the handler should be applied.
	 * @param function $handler
	 *   Function should accept two parameters $request and $response
	 */
	public function get($uri, $handler) {
		$this->handlers['GET'][] = new RouteHandler($uri, $handler);
	}

	/**
	 * Add a POST request handler for the specified URI template.
	 *
	 * @param string $uri
	 *   The URI template for requests to which the handler should be applied.
	 * @param function $handler
	 *   Handler function. Will get passed two parameters: $request and $response.
	 */
	public function post($uri, $handler) {
		$this->handlers['POST'][] = new RouteHandler($uri, $handler);
	}

	/**
	 * Process the specified request method, URI, body and headers.
	 *
	 * @param string $method
	 * @param string $uri
	 */
	public function process($method, $uri) {
		$request = $this->getMessageBuilder()->getRequest();

		if (!isset($this->handlers[$method])) {
			return;
		}

		foreach ($this->handlers[$method] as $handler) {
			if ($handler->getUri()->matches($uri)) {
				$handler->invoke();
			}
		}
	}

	/*
	 * ===========================================================================
	 * Dependencies
	 * ===========================================================================
	 */

	/**
	 * Getter for the {@link MessageBuilder} used by Router. If not explictely
	 * set, a {@link PhpEnvMessageBuilder} is used.
	 *
	 * @return MessageBuilder
	 */
	public function getMessageBuilder() {
		if ($this->messageBuilder === null) {
			$this->messageBuilder = new PhpEnvMessageBuilder();
		}
		return $this->messageBuilder;
	}

	/**
	 * Setter for the {@link MessageBuilder} used by the Router to construct the 
	 * {@link Request} and {@link Response} messages that are passed to the route 
	 * handlers.
	 *
	 * @param MessageBuilder $messageBuilder
	 */
	public function setMessageBuilder(MessageBuilder $messageBuilder) {
		$this->messageBuilder = $messageBuilder;
	}

}
