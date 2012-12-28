<?php
/**
 * =============================================================================
 * Copyright (c) 2013, Philip Graham
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
namespace zeptech\rest;

use \Exception;

/**
 * Default REST exception handler. Creates a 500 response.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class DefaultExceptionHandler implements ExceptionHandler
{

    public function handleException(
        Exception $e,
        Request $request,
        Response $response
    ) {
        
        $response->clearHeaders();

        $code = 500;
        $hdrMsg = RestException::$HEADER_MESSAGES[$code];
        $msg = RestException::$MESSAGES[$code];
        $hdr = "HTTP/1.1 $code $hdrMsg";

        $response->header($hdr);
        $response->setData($msg);
    }
}
