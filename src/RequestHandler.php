<?php
/**
 * =============================================================================
 * Copyright (c) 2010, Philip Graham
 * All rights reserved.
 *
 * This file is part of php-rest-server and is licensed by the Copyright holder
 * under the 3-clause BSD License.  The full text of the license can be found in
 * the LICENSE.txt file included in the root directory of this distribution or
 * at the link below.
 * =============================================================================
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 */
namespace zpt\rest;

use zpt\rest\message\Request;

/**
 * Interface for objects that handle a RESTful resource request.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
interface RequestHandler {

  /** 
   * Handle a DELETE request for a resource.
   *
   * @param string $uri The requested resource.
   */
  public function delete(Request $request, Response $response);
 

  /**
   * Handle a GET request for a resource.
   *
   * @param string $uri The requested resource.
   * @param array $params Any given query parameters.
   */
  public function get(Request $request, Response $response);

  /**
   * Handle a POST request for a resource.
   *
   * @param string $uri The requested resource.
   * @param string $data The data posted for the resource.
   */
  public function post(Request $request, Response $response);

  /**
   * Handle a PUT request for a resource.
   *
   * @param string $uri The requested resource.
   * @param string $data The data to put for the resource.
   */
  public function put(Request $request, Response $response);

}
