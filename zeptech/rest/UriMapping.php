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

/**
 * This class encapsulates a request mapping.  A mapping consists of an URL
 * template and a handler used to process requests with URIs that satisfy the
 * given template.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class UriMapping {

  private $_template;
  private $_handler;

  public function __construct($uriTemplate, $handler) {
    $this->_template = new UriTemplate($uriTemplate);
    $this->_handler = $handler;
  }

  public function getHandler() {
    return $this->_handler;
  }

  public function getTemplate() {
    return $this->_template;
  }

}
