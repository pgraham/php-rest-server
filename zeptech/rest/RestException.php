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
namespace zeptech\rest;

use \Exception;

/**
 * Exception for erroneous requests to a rest server.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class RestException extends Exception {

  public static $MESSAGES = array(
    400 => 'The request could not be understood by the server due to malformed syntax.',
    401 => 'You are not authorized to perform the request action.',
    403 => 'The attempt action is forbidden and should not be retried. Authentication will not help.',
    404 => 'The requested resource could not be found.',
    405 => 'Cannot perform the requested action on the specified resource.',
    500 => 'The server encountered an unexpected condition which prevented it from fulfilling the request.'
  );

  public static $HEADER_MESSAGES = array(
    400 => 'Bad Request',
    401 => 'Unauthorized',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    500 => 'Internal Server Error'
  );

  private $_hdrMsg;
  private $_hdrs = array();

  public function __construct($code, array $hdrs = null) {
    parent::__construct(self::$MESSAGES[$code], $code);
    $this->_hdrMsg = self::$HEADER_MESSAGES[$code];

    if ($hdrs) {
      $this->_hdrs = $hdrs;
    }
  }

  public function getHeaderMessage() {
    return $this->_hdrMsg;
  }

  public function getHeaders() {
    return $this->_hdrs;
  }

  public function header($hdr) {
    $this->_hdrs[] = $hdr;
  }

}
