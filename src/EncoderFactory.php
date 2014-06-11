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
 * This class encapsulates the collection of available encoders.
 * This class implements the Factory Method pattern.  The concrete factory is
 * a private singleton instance of this class.
 *
 * @author Philip Graham <philip@zeptech.ca>
 */
class EncoderFactory {

  /* The factory instance. */
  private static $_instance;

  /**
   * Retrieve a response encoder for the given mime type.
   *
   * @param string $mimeType
   * @return ResponseEncoder
   */
  public static function getEncoder(AcceptType $type) {
    $factory = self::getInstance();

    $encoders = $factory->getEncoders();
    foreach ($encoders AS $encoder) {
      if ($encoder->supports($type)) {
        return $encoder;
      }
    }
    return null;
  }

  /**
   * Instance method for the factory singleton.
   *
   * @return EncoderFactory
   */
  public static function getInstance() {
    if (self::$_instance === null) {
      self::$_instance = new EncoderFactory();
    }
    return self::$_instance;
  }

  /*
   * ===========================================================================
   * Instance
   * ===========================================================================
   */

  private $_binaryEncoder;
  private $_htmlEncoder;
  private $_jsonEncoder;
  private $_textEncoder;

  private $_encoders = array();

  protected function __construct() {
    $this->_binaryEncoder = new BinaryEncoder();
    $this->_htmlEncoder = new HtmlEncoder();
    $this->_jsonEncoder = new JsonEncoder();
    $this->_textEncoder = new TextEncoder();

    $this->_encoders = array(
      $this->_binaryEncoder,
      $this->_htmlEncoder,
      $this->_jsonEncoder,
      $this->_textEncoder
    );
  }

  public function getBinaryEncoder() {
    return $this->_binaryEncoder;
  }

  public function getHtmlEncoder() {
    return $this->_htmlEncoder;
  }

  public function getJsonEncoder() {
    return $this->_jsonEncoder;
  }

  public function getTextEncoder() {
    return $this->_textEncoder;
  }

  protected function getEncoders() {
    return $this->_encoders;
  }

}
