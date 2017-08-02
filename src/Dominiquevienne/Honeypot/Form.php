<?php
/**
 * Created by PhpStorm.
 * User: dvienne
 * Date: 22/06/2017
 * Time: 10:42
 */

namespace Dominiquevienne\Honeypot;


class Form {

  private $_timeCheckSessionVarName     = 'honeypotTimeCheckStart';
  private $_methodSessionVarName        = 'honeypotMethod';
  private $_honeypotInputSessionVarName = 'honeypotInputName';
  private $_honeypotInputMask           = '<input class="[$honeypotInputClass]" type="[$honeypotInputType]" name="[$honeypotInputName]" autocomplete="off" value="" />';
  private $_tokenInputMask              = '<input class="[$tokenInputClass]" type="[$tokenInputType]" name="[$tokenInputName]" autocomplete="off" value="[$tokenInputValue]" />';
  private $_honeypotInputClass          = 'hide';
  private $_honeypotInputType           = 'text';
  private $_honeypotInputName           = null;
  private $_honeypotInputNames          = [
    'phoneNumber',
    'address',
    'zipCode',
    'contactPerson',
    'completeName',
  ];
  private $_tokenInputClass             = 'hide';
  private $_tokenInputType              = 'text';
  private $_tokenInputName              = 'honeypotToken';
  private $_method                      = 'POST';
  private $_tokenVarName                = 'honeypotToken';



  public function __construct($config = []) {
    if(empty($_SESSION)) {
      session_start();
    }

    if(is_array($config)) {
      if(!empty($config['honeypotInputMask'])) {
        $this->setHoneypotInputMask($config['honeypotInputMask']);
      }
      if(!empty($config['honeypotInputClass'])) {
        $this->setHoneypotInputClass($config['honeypotInputClass']);
      }
      if(!empty($config['honeypotInputType'])) {
        $this->setHoneypotInputType($config['honeypotInputType']);
      }
      if(!empty($config['honeypotInputName'])) {
        $this->setHoneypotInputName($config['honeypotInputName']);
      }
      if(!empty($config['honeypotInputNames'])) {
        $this->setHoneypotInputNames($config['honeypotInputNames']);
      }
      if(!empty($config['formMethod'])) {
        $this->setFormMethod($config['formMethod']);
      }
    }
  }


  /**
   * Getter for honeypotInputMask
   *
   * @return string
   */
  public function getHoneypotInputMask()
  {
    return $this->_honeypotInputMask;
  }


  /**
   * Setter for honeypotInputMask
   *
   * @param $mask
   *
   * @return $this
   */
  public function setHoneypotInputMask($mask)
  {
    $this->_honeypotInputMask = $mask;

    return $this;
  }


  /**
   * Getter for honeypotInputClass
   *
   * @return string
   */
  public function getHoneypotInputClass()
  {
    return $this->_honeypotInputClass;
  }


  /**
   * Setter for honeypotInputClass
   *
   * @param $class
   *
   * @return $this
   */
  public function setHoneypotInputClass($class)
  {
    $this->_honeypotInputClass  = $class;

    return $this;
  }


  /**
   * Getter for honeypotInputType
   *
   * @return string
   */
  public function getHoneypotInputType()
  {
    return $this->_honeypotInputType;
  }


  /**
   * Setter for honeypotInputType
   *
   * @param $type
   *
   * @return $this
   */
  public function setHoneypotInputType($type)
  {
    $this->_honeypotInputType = $type;

    return $this;
  }


  /**
   * Getter for honeypotInputName
   *
   * @return null|string
   */
  public function getHoneypotInputName()
  {
    return $this->_honeypotInputName;
  }


  /**
   * Setter for honeypotInputName
   *
   * @param $name
   *
   * @return $this
   */
  public function setHoneypotInputName($name)
  {
    $this->_honeypotInputName = $name;

    return $this;
  }


  /**
   * Getter for honeypotInputNames
   *
   * @return array
   */
  public function getHoneypotInputNames()
  {
    return $this->_honeypotInputNames;
  }


  /**
   * Setter for honeypotInputNames
   *
   * @param $names
   */
  public function setHoneypotInputNames($names)
  {
    if(!is_array($names)) {
      $names  = [
        $names,
      ];
    }

    $this->_honeypotInputNames  = $names;
  }


  /**
   * Getter for tokenInputMask
   *
   * @return string
   */
  public function getTokenInputMask()
  {
    return $this->_tokenInputMask;
  }


  /**
   * Setter for tokenInputMask
   *
   * @param $mask
   *
   * @return $this
   */
  public function setTokenInputMask($mask)
  {
    $this->_tokenInputMask = $mask;

    return $this;
  }


  /**
   * Getter for tokenInputClass
   *
   * @return string
   */
  public function getTokenInputClass()
  {
    return $this->_tokenInputClass;
  }


  /**
   * Setter for tokenInputClass
   *
   * @param $class
   *
   * @return $this
   */
  public function setTokenInputClass($class)
  {
    $this->_tokenInputClass  = $class;

    return $this;
  }


  /**
   * Getter for tokenInputType
   *
   * @return string
   */
  public function getTokenInputType()
  {
    return $this->_tokenInputType;
  }


  /**
   * Setter for tokenInputType
   *
   * @param $type
   *
   * @return $this
   */
  public function setTokenInputType($type)
  {
    $this->_tokenInputType = $type;

    return $this;
  }


  /**
   * Getter for tokenInputName
   *
   * @return null|string
   */
  public function getTokenInputName()
  {
    return $this->_tokenInputName;
  }


  /**
   * Getter for tokenInputValue
   *
   * @return string
   */
  public function getTokenInputValue()
  {
    $token  = Helpers::generateRandomString(24);
    $_SESSION[$this->getTokenSessionVarName()]  = $token;

    return $token;
  }


  /**
   * Used to store current time in Session in order to measure form completion time
   */
  public function timeCheck()
  {
    $_SESSION[$this->getTimeCheckSessionVarName()]  = time();

    if(empty($_SESSION[$this->getMethodSessionVarName()])) {
      $this->_registerMethod();
    }
  }


  /**
   * Getter for timeCheckSessionVarName
   *
   * @return string
   */
  public function getTimeCheckSessionVarName()
  {
    return $this->_timeCheckSessionVarName;
  }


  /**
   * Getter for honeypotInputSessionVarname
   *
   * @return string
   */
  public function getHoneypotInputSessionVarName()
  {
    return $this->_honeypotInputSessionVarName;
  }


  /**
   * Getter for Token var name in SESSION
   *
   * @return string
   */
  public function getTokenSessionVarName()
  {
    return $this->_tokenVarName;
  }


  /**
   * Will generate honeypotInput and save its name in Session
   */
  private function _generateHoneypotInput()
  {
    $honeypotInputName = $this->getHoneypotInputName();
    if(empty($honeypotInputName)) {
      $names  = $this->getHoneypotInputNames();
      $name   = $names[rand(0, count($names)-1)];
      $name   .= Helpers::generateRandomString(3);
      $this->setHoneypotInputName($name);
    }

    $_SESSION[$this->getHoneypotInputSessionVarName()]  = $this->getHoneypotInputName();
  }


  /**
   * Generates the honeypot input field
   *
   * @return mixed
   */
  public function honeypotInput()
  {
    $this->_generateHoneypotInput();

    $mask   = $this->getHoneypotInputMask();
    $input  = preg_replace_callback(
      '/\[\$([^\]]+)\]/si',
      function($m) {
        $functionName = 'get' . $m[1];
        return $this->$functionName();
      },
      $mask
    );

    if(empty($_SESSION[$this->getMethodSessionVarName()])) {
      $this->_registerMethod();
    }

    return $input;
  }

  public function tokenInput()
  {
    $mask   = $this->getTokenInputMask();
    $input  = preg_replace_callback(
      '/\[\$([^\]]+)\]/si',
      function($m) {
        $functionName = 'get' . $m[1];
        return $this->$functionName();
      },
      $mask
    );

    return $input;
  }


  /**
   * Returns the different needed inputs
   *
   * @return mixed
   */
  public function inputs()
  {
    return $this->honeypotInput() . $this->tokenInput();
  }


  /**
   * Getter for methodSessionVarName
   *
   * @return string
   */
  public function getMethodSessionVarName()
  {
    return $this->_methodSessionVarName;
  }


  /**
   * Used to register form method
   */
  private function _registerMethod()
  {
    $_SESSION[$this->getMethodSessionVarName()] = $this->getFormMethod();
  }


  /**
   * Setter for method
   *
   * @return string
   */
  public function getFormMethod()
  {
    return $this->_method;
  }


  /**
   * Setter for method
   *
   * @param $method
   *
   * @return $this
   */
  public function setFormMethod($method)
  {
    $this->_method  = strtoupper($method);

    return $this;
  }

}