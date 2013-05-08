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

/**
 * This class encapsulates a URI template.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class UriTemplate {

  private $_tmpl;
  private $_matchRE;
  private $_paramNames = array();

  public function __construct($uriTemplate) {
    $this->_tmpl = $uriTemplate;

    if ($this->_tmpl === '/') {
      $this->_matchRE = '/^\/$/';
      return;
    }

    if(preg_match_all('/\/\{([^}]+)\}/', $this->_tmpl, $matches)) {
      $this->_paramNames = $matches[1];
    }

    $tmplCmps = explode('/', ltrim($this->_tmpl, '/'));
    $escaped = array();
    foreach ($tmplCmps as $cmp) {
      if (preg_match('/\{.+\}/', $cmp)) {
        $escaped[] = '([^\/]+)';
      } else {
        $escaped[] = preg_quote($cmp);
      }
    }

    $this->_matchRE = "/^\/" . implode('\/', $escaped) . "$/";
  }

  public function matches($uri, &$params) {
    if (preg_match($this->_matchRE, $uri, $matches)) {
      array_shift($matches);

      $params = array();
      foreach ($this->_paramNames AS $idx => $param) {
        $params[$param] = $matches[$idx];
      }
      return true;
    }
    return false;
  }
}
