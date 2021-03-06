# honeypot
This PHP library is used to manage honeypots in HTML forms. It will create the input form and do the necessary checks. 

## Installation
### Recommended
Go to you project root directory and use composer using this command
```
composer require dominiquevienne/honeypot
```
Then create your application bootstrap using this kind of code
```php
<?php
require __DIR__ . '/vendor/autoload.php';

$oForm  = new Dominiquevienne\Honeypot\Form();
echo $oForm->inputs();
```
### Manual installation
- Download latest stable release on [Github](https://github.com/dominiquevienne/honeypot/releases)
- Uncompress the downloaded file
- Place content into your project
- Use similar code to load object
```php
<?php
require __DIR__ . '/honeypot/src/Honeypot.php';
require __DIR__ . '/honeypot/src/Form.php';

$oForm  = new Dominiquevienne\Honeypot\Form();
echo $oForm->inputs();
```
### Laravel
Using honeypot in Laravel is as simple as a
```
composer require dominiquevienne/honeypot
```
and add the following lines in your class
```php
<?php
use \Dominiquevienne\Honeypot\Form;

class yourController {
  public function show() {
    /** some code of yours */
    $oForm  = new Form();
    return $oForm->inputs();
  }
}
```
### Drupal 8
First of all, go to your root directory of your Drupal 8 project and type
```
composer require dominiquevienne/honeypot
```
In the Form controller (where you build your form), add
```
use Dominiquevienne\Honeypot\Form;
```
in order to gain access to Honeypot Form constructor.

Instantiate your form using the ``drupalForm`` config array key set to ``TRUE``

Checks are done in the validate function using standard functions. 

## How it works
Once the package is installed the honeypot consists in enabling two steps
### Form rendering
Where you will trigger Form::timeCheck() which will store date-time of the Form rendering and trigger Form::honeypotInput() used to return the honeypot form element. 

Any of those two options are mandatory. 
```php
<?php
$oForm          = new Dominiquevienne\Honeypot\Form();
$oForm->timeCheck();
$honeypotInputs = $oForm->inputs();
?>
<html>
<?php 
// All your HTML code before your form
?>
<form action="yourLandingPage.php" method="post">
<?php
// The standard fields of your form
echo $honeypotInputs;
?>
<input type="submit"/>
</html>
```
Be aware that you'll need to hide the honeypot field. To do that, you'll have three solutions
- use CSS (default class is hide)
- remove the element from the DOM using JS (Pure Javascript, jQuery or other). This is already done by using default function ``$oForm->inputs()`` or by using ``$oForm->getHoneypotScript()`` which would return a script in pure javascript that would remove the honeypot field from the DOM. 
### Form action page
Before you do the real job in your script of the landing page of your form (`action` attribute of Form element), you will have to use this code
```php
<?php
$oHoneypot  = new Dominiquevienne\Honeypot\Honeypot();
$checks     = $oHoneypot->checks();
if(!empty($checks)) {
  die('Your are a spammer');
}
// your code
```
## Available configuration
When you create the object, you have the ability to pass config values through an array. 
```php
<?php
$config = [
  'honeypotInputClass'  => 'myCssClass',
  'honeypotInputNames'  => [
    'name1',
    'name2',
  ],
  'formMethod'          => 'GET',
];
$oForm  = new Dominiquevienne\Honeypot\Form($config);
```
### Form
#### honeypotInputMask
This is the mask used to render the input field. You can use Form::getHoneypotInputMask() to get the current value. 
#### honeypotInputClass
This is the CSS class used on the honeypot input field. 
#### honeypotInputType
This is the HTML type of the input field
#### honeypotInputName
Way to force a specific name
#### honeypotInputNames
Array containing a list in which honeypot will take a random name followed by a hash. 
#### formMethod
HTTP Method used to send the form
### Honeypot
#### minFormCompletionTime
Time in seconds under which a form subscriber will be considered as a bot
#### maxFailureAttempts
Quantity of accepted failures per session before considered as a bot. When the number is reached and failureCheck is ON, every attempt will be considered as a failure during all the session lifetime. 
#### maxAttempts
Quantity of accepted attempts per session before considered as a bot. When the number is reached and quantityCheck is ON, every attempt will be considered as a failure during all the session lifetime. 
#### checks
Array of checks to be made when submitting form. By default, checks are ```['timeCheck','honeypotCheck','tokenCheck','failureCheck','quantityCheck']```
#### drupalForm
If set to ``TRUE``, ``Form::inputs()`` will return a Drupal FAPI array instead of raw HTML