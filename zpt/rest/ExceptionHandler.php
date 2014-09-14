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
namespace zpt\rest;

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
     * Indicate whether or not the handler can handle an exception of the given
     * type.
     *
     * @param Exception $e
     * @return boolean
     */
    public function handles(Exception $e);

    /**
     * Use the given Exception instance and request to data to populate a
     * response object.
     *
     * Return a truthy value to indicate that the given
     * exception has been handled. Anything else will cause subsequent exception
     * handlers to be invoked.
     */
    public function handle(Exception $e, Request $request, Response $response);

}
