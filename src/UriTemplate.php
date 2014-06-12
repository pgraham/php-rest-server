<?php
/*
 * Copyright (c) 2014, Philip Graham
 * All rights reserved.
 *
 * This file is part of php-rest-server. For the full copyright and license
 * information please view the LICENSE file that was distributed with this
 * source code.
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

  public function matches($uri, &$params = false) {
    if (!$params) {
      $params = [];
    }
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
