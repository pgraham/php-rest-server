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
 * Interface for classes that transform a specific type of exception into a
 * REST response.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
interface ExceptionHandler
{

    /**
     * Use the given Exception instance and request to data to populate a
     * response object.
     */
    public function handleException(
        Exception $e,
        Request $request,
        Response $response
    );

}
