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

use \zeptech\rest\ExceptionHandler;
use \zeptech\rest\Request;
use \zeptech\rest\Response;
use \Exception;

/**
 * REST server exception handler for RestExceptions.  Builds a 40X response
 * based on the data in the caught Exception.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class RestExceptionHandler implements ExceptionHandler
{

    public function handleException(
        Exception $e,
        Request $request,
        Response $response
    ) {

        $response->clearHeaders();

        $hdr = "HTTP/1.1 {$e->getCode()} {$e->getHeaderMessage()}";
        $response->header($hdr);
        foreach ($e->getHeaders() as $hdr) {
            $response->header($hdr);
        }
        $response->setData($e->getMessage());
    }
}
