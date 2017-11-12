# password-strength-validator
Validates if a password is strong enough by checking if it contains digits, symbols and letters in different casing.


| **master** | **develop**  |
|------------|--------------|
| [![Build Status](https://travis-ci.org/kanellov/password-strength-validator.svg?branch=master)](https://travis-ci.org/kanellov/password-strength-validator) | [![Build Status](https://travis-ci.org/kanellov/password-strength-validator.svg?branch=develop)](https://travis-ci.org/kanellov/password-strength-validator) |

## Installation

Install with composer:

```bash
$ composer require kanellov/password-strength-validator
```

## Description
This package provides a validator for checking password strength.
It can be customized to check for the following:

- Password contains at least one digit character
- Password contains at least one uppercase character
- Password contains at least one lowercase character
- Password contains at least one symbol character
- Password contains at least either one digit or symbol character

Also, when validating for symbols, you can exclude some of them from the validation (See [Excluding symbol characters](#Excluding symbol characters)).

## Usage

### Function

```php
require 'vendor/autoload.php';

// force password to contain at least one digit and one uppercase char
$flags = KNLV_PWD_CONTAIN_DGT | KNLV_PWD_CONTAIN_UC;
$password = "somePasswordNotContainingDigits";

$code = 0;
$message = '';
$is_valid = true;
try {
    \Knlv\password_strength($password, $flags);
} catch(\ErrorException $e) {
    $is_valid = false;
    $code = $e->getCode();
    $message =  $e->getMessage();
}

var_dump($is_valid, $code, $message);
/* --- RESULTS ---
 * bool(false)
 * int(1)
 * string(50) "Password must contain at least one digit character"
 */
```

### Zend Validator

In order to use this library as Zend Validator use must install `zendframework/zend-validator` package.

```bash
$ composer require zendframework/zend-validator
```

```php
require 'vendor/autoload.php';

use \Knlv\Validator\PasswordStrength;

$password = "somePasswordNotContainingDigits";
$validator = new PasswordStrength(array('flags' => KNLV_PWD_CONTAIN_DGT | KNLV_PWD_CONTAIN_UC));
$is_valid = $validator->isValid($password);
$messages = $validator->getMessages();

var_dump($is_valid, $messages);

/* --- RESULTS ---
 * bool(false)
 * array(1) {
 *   [1]=>
 *   string(50) "Password must contain at least one digit character"
 * }
 */
```

### Excluding symbol characters

When `\Knlv\password_strength` function is called with `KNLV_PWD_CONTAIN_SYM` or `KNLV_PWD_CONTAIN_DGT_OR_SYM` 
flags it checks if password contains at least one of the ```!"#$&'()*+,-./:;<=>?@[\\]^_`{|}~``` symbol characters.

If don't want your password to contain some of these symbols, you can exclude them from the check by passing an extra argument 
with the symbols you want to exclude. For example, the following shows how to exclude the `@` and `!` symbols from validation:

```php 
require 'vendor/autoload.php';

// force password to contain at least one symbol char
$flags = KNLV_PWD_CONTAIN_SYM;
$password = "p@ssword!";
$exclude = "@!"; // exclude @ and ! symbols

$code = 0;
$message = '';
$is_valid = true;
try {
    \Knlv\password_strength($password, $flags, $exclude);
} catch(\ErrorException $e) {
    $is_valid = false;
    $code = $e->getCode();
    $message =  $e->getMessage();
}

var_dump($is_valid, $code, $message);
/* --- RESULTS ---
 * bool(false)
 * int(8)
 * string(51) "Password must contain at least one symbol character"
 */
 
 
 // Using validator
 
$password = "p@ssword!";
$validator = new \Knlv\Validator\PasswordStrength(array(
    'flags' => KNLV_PWD_CONTAIN_SYM,
    'excludedSymbols' => '@!',
));
$is_valid = $validator->isValid($password);
$messages = $validator->getMessages();

var_dump($is_valid, $messages);
/* --- RESULTS ---
 * bool(false)
 * array(1) {
 *   [8]=>
 *   string(51) "Password must contain at least one symbol character"
 * }
 */
```

## License
The GNU GENERAL PUBLIC LICENSE Version 3. Please see [License File](LICENSE) for more information.
