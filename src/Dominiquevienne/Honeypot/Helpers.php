<?php
/**
 * Created by PhpStorm.
 * User: dvienne
 * Date: 22/06/2017
 * Time: 10:44
 */

namespace Dominiquevienne\Honeypot;


class Helpers {

  /**
   * Generates a random string
   * Source: https://stackoverflow.com/questions/4356289/php-random-string-generator
   *
   * @param int $length
   *
   * @return string
   */
  public static function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}