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
echo $oForm->honeypotInput();
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

$oForm  = new Dominiquevienne\Honeypot\Honeypot();
echo $oForm->honeypotInput();
```
### Laravel
Using honeypot in Laravel is as simple as a
```
composer require dominiquevienne/honeypot
```
and add the following lines in your class
```php
<?php
use \Dominiquevienne\Honeypot\Honeypot;

class yourController {
  public function show() {
    /** some code of yours */
    $oForm  = new Form();
    return $oForm->honeypotInput();
  }
}
```
