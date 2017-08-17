<?php
/**
 * Created by PhpStorm.
 * User: dvienne
 * Date: 17/08/2017
 * Time: 09:41
 *
 * To use this example, please do the following:
 * - create a directory in your webroot
 * - use your terminal / command line in order to get to this directory and launch 'composer require dominiquevienne/honeypot'
 * - copy the current file in this directory
 *
 * If you need to destroy the session for test purposes, just call this page with ?destroy=1 GET parameter
 */

if(!empty($_GET['destroy'])) {
  session_start();
  var_dump($_SESSION);
  session_destroy();
  var_dump($_SESSION);
  die();
}
require __DIR__ . '/vendor/autoload.php';

$oForm  = new Dominiquevienne\Honeypot\Form();

if(!empty($_POST)) {
  $config = [
    'minFormCompletionTime' => 20,
  ];
  $oHoneypot  = new Dominiquevienne\Honeypot\Honeypot($config);
  $checks     = $oHoneypot->checks(true);
  var_dump($checks);
  die();
}

$oForm->timeCheck();
$inputs  = $oForm->inputs();
?>
<html>
<?php
// All your HTML code before your form
?>
<form action="simpleForm.php" method="post">
  <?php
  // The standard fields of your form
  echo $inputs;
  ?>
  <input type="submit"/>
  <?php
  var_dump($_SESSION);
  ?>
</html>
