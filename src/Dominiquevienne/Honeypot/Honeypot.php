<?php
/**
 * Created by PhpStorm.
 * User: dvienne
 * Date: 22/06/2017
 * Time: 10:25
 */

namespace Dominiquevienne\Honeypot;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class Honeypot {

  private $_minFormCompletionTime = 10;
  private $_maxFailureAttempts    = 3;
  private $_maxAttempts           = 10;
  private $_checks                = [
                                      'timeCheck',
                                      'honeypotCheck',
                                      'tokenCheck',
                                      'failureCheck',
                                      'quantityCheck',
                                    ];
  private $_availableChecks       = [
                                      'timeCheck',
                                      'honeypotCheck',
                                      'tokenCheck',
                                      'failureCheck',
                                      'quantityCheck',
                                    ];
  private $_logger;
  private $_logPath               = 'logs/honeypot.logs';



  public function __construct($config = [])
  {
    if(empty($_SESSION)) {
      session_start();
    }

    if(!empty($config['minFormCompletionTime']) AND is_int($config['minFormCompletionTime'])) {
      $this->setMinFormCompletionTime($config['minFormCompletionTime']);
    }

    if(!empty($config['maxFailureAttempts']) AND is_int($config['maxFailureAttempts'])) {
      $this->setMaxFailureAttempts($config['maxFailureAttempts']);
    }

    if(!empty($config['maxAttempts']) AND is_int($config['maxAttempts'])) {
      $this->setMaxAttempts($config['maxAttempts']);
    }

    if(!empty($config['checks'])) {
      $this->setChecks($config['checks']);
    }

    $this->_logger  = new Logger('honeypotLogger');
    $this->_logger->pushHandler(new StreamHandler($this->getLogPath()));
  }


  /**
   * Getter for minFormCompletionTime
   *
   * @return int
   */
  public function getMinFormCompletionTime()
  {
    return $this->_minFormCompletionTime;
  }


  /**
   * Setter for minFormCompletionTime
   *
   * @param $time
   *
   * @return $this|bool
   */
  public function setMinFormCompletionTime($time)
  {
    if(is_int($time)) {
      $this->_minFormCompletionTime = $time;

      return $this;
    } else {
      return FALSE;
    }
  }


  /**
   * Getter for maxFailureAttempts
   *
   * @return int
   */
  public function getMaxFailureAttempts()
  {
    return $this->_maxFailureAttempts;
  }


  /**
   * Setter for maxFailureAttempts
   *
   * @param $attempts
   *
   * @return $this|bool
   */
  public function setMaxFailureAttempts($attempts)
  {
    if(is_int($attempts)) {
      $this->_maxFailureAttempts  = $attempts;

      return $this;
    } else {
      return FALSE;
    }
  }


  /**
   * Getter for maxAttempts
   *
   * @return int
   */
  public function getMaxAttempts()
  {
    return $this->_maxAttempts;
  }


  /**
   * Setter for maxAttempts
   *
   * @param $attempts
   *
   * @return $this|bool
   */
  public function setMaxAttempts($attempts)
  {
    if(is_int($attempts)) {
      $this->_maxAttempts = $attempts;

      return $this;
    } else {
      return FALSE;
    }
  }


  /**
   * Getter for checks to be made on submission
   *
   * @return array
   */
  public function getChecks()
  {
    return $this->_checks;
  }


  /**
   * Setter for checks to be made on form submission
   *
   * @param $checks
   *
   * @return $this|bool
   */
  public function setChecks($checks)
  {
    if(!is_array($checks)) {
      return FALSE;
    } else {
      foreach($checks as $check) {
        if(!in_array($check, $this->_availableChecks)) {
          unset($checks[$check]);
        }
      }
      if(!empty($checks)) {
        $this->_checks  = $checks;

        return $this;
      } else {
        return FALSE;
      }
    }
  }


  /**
   * Getter for logPath
   *
   * @return string
   */
  public function getLogPath()
  {
    return $this->_logPath;
  }


  /**
   * Setter for logPath
   *
   * @param $path
   */
  public function setLogPath($path)
  {
    $this->_logPath = $path;
  }


  /**
   * Main function called to launch all checks
   *
   * @param bool $full
   *
   * @return array|bool|mixed
   */
  public function checks($full = FALSE)
  {
    $checks       = [];
    $now          = time();

    foreach($this->getChecks() as $checkName) {
      $checks[$checkName] = $this->$checkName();
      if(empty($full) AND empty($checks[$checkName])) {

        $message  = $_SERVER['REMOTE_ADDR'] . ' - Failed: ' . $checkName . ' - ' . $now;
        $this->_logger->info($message);

        return $checks[$checkName];
      }
    }

    if(!empty($full)) {
      $message  = $_SERVER['REMOTE_ADDR'] . ' - Failed: ' . json_encode($checks) . ' - ' . $now;
      $this->_logger->info($message);

      return $checks;
    } else {
      return TRUE;
    }
  }


  /**
   * Will return if form as been completed by spammer of not regarding form completion time
   * No logging when using directly the function
   *
   * @return bool
   */
  public function timeCheck()
  {
    $form = new Form();
    $now  = time();

    if(($_SESSION[$form->getTimeCheckSessionVarName()]+$this->getMinFormCompletionTime())>$now) {
      $this->_increaseFailureCounter();
      return FALSE;
    } else {
      return TRUE;
    }
  }


  /**
   * Will check if honeypot field has been filled
   * No logging when using directly the function
   *
   * @return bool
   */
  public function honeypotCheck()
  {
    $form       = new Form();
    $method     = strtoupper($_SESSION[$form->getMethodSessionVarName()]);
    $inputName  = $_SESSION[$form->getHoneypotInputSessionVarName()];

    if($method=='GET') {
      if(!empty($_GET[$inputName])) {
        $this->_increaseFailureCounter();
        return FALSE;
      } else {
        return TRUE;
      }
    } elseif($method=='POST') {
      if(!empty($_POST[$inputName])) {
        $this->_increaseFailureCounter();
        return FALSE;
      } else {
        return TRUE;
      }
    }
  }


  /**
   * Checks if token is valid
   *
   * @return bool
   */
  public function tokenCheck()
  {
    $form         = new Form();
    $method       = strtoupper($_SESSION[$form->getMethodSessionVarName()]);
    $sessionToken = $_SESSION[$form->getTokenSessionVarName()];

    if($method=='GET') {
      if(($_GET[$form->getTokenInputName()]==$sessionToken) AND !empty($sessionToken)) {
        $this->_resetToken();
        return TRUE;
      } else {
        $this->_resetToken();
        $this->_increaseFailureCounter();
        return FALSE;
      }
    } elseif($method=='POST') {
      if(($_POST[$form->getTokenInputName()]==$sessionToken) AND !empty($sessionToken)) {
        $this->_resetToken();
        return TRUE;
      } else {
        $this->_resetToken();
        $this->_increaseFailureCounter();
        return FALSE;
      }
    }
    $this->_increaseFailureCounter();
    return FALSE;
  }


  /**
   * Used to destroy token in session
   *
   * @return $this
   */
  private function _resetToken() {
    $form         = new Form();
    $_SESSION[$form->getTokenSessionVarName()]  = null;

    return $this;
  }


  /**
   * Checks maximum failed attempts per session
   *
   * @return bool
   */
  public function failureCheck()
  {
    $oForm  = new Form();
    if($_SESSION[$oForm->getFailureAttemptsSessionVarName()]>$this->getMaxFailureAttempts()) {
      return FALSE;
    } else {
      return TRUE;
    }
  }


  /**
   * Checks maximum attempts per session
   *
   * @return bool
   */
  public function quantityCheck()
  {
    $oForm  = new Form();
    if($_SESSION[$oForm->getAttemptsSessionVarName()]>$this->getMaxAttempts()) {
      $this->_increaseFailureCounter();
      return FALSE;
    } else {
      if(empty($_SESSION[$oForm->getFailureAttemptsSessionVarName()])) {
        $_SESSION[$oForm->getFailureAttemptsSessionVarName()] = 0;
      }
      return TRUE;
    }
  }


  /**
   * Used to increase failure counter
   *
   * @return $this
   */
  private function _increaseFailureCounter()
  {
    $oForm    = new Form();

    if(!empty($_SESSION[$oForm->getFailureAttemptsSessionVarName()])) {
      $_SESSION[$oForm->getFailureAttemptsSessionVarName()]++;
    } else {
      $_SESSION[$oForm->getFailureAttemptsSessionVarName()] = 1;
    }

    return $this;
  }
}