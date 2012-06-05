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

  private static $_msgs = array(
    404 => 'The requested resource could not be found.',
    405 => 'Cannot perform the requested action on the specified resource.'
  );

  private static $_hdrMsgs = array(
    404 => 'Not Found',
    405 => 'Method Not Allowed'
  );

  private $_hdrMsg;
  private $_hdrs = array();

  public function __construct($code) {
    parent::__construct(self::$_msgs[$code], $code);
    $this->_hdrMsg = self::$_hdrMsgs[$code];
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
