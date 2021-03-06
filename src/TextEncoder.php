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

use zpt\rest\message\Response;

/**
 * This class encapsulates a response encoder for text/* media types.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class TextEncoder implements ResponseEncoder {

  public function supports(AcceptType $type) {
    return $type->matches('text/*');
  }

  public function encode(Response $response) {
    $response->header('Content-Type: text/plain');
    return (string) $response->getData();
  }

}
