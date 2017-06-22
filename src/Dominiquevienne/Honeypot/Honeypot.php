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
  private $_checks                = ['timeCheck','honeypotCheck'];
  private $_availableChecks       = ['timeCheck','honeypotCheck'];
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
        return FALSE;
      } else {
        return TRUE;
      }
    } elseif($method=='POST') {
      if(!empty($_POST[$inputName])) {
        return FALSE;
      } else {
        return TRUE;
      }
    }
  }
}