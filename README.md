# Bank Accounts Validator

## Installation

Vía Composer

```bash
composer require reyesoft/php-bank-account
```

## Example

```php
use BankAccounts\Ar\ArBankAccount;

$valid = (new ArBankAccount('2850396540094708965758');
```

## Available countries

Based on [ISO alpha 2 characters](https://www.nationsonline.org/oneworld/country_code_list.htm).
* Argentina (AR)
* México (MX)
* Venezuela (VE)

## Development

```bash
./vendor/bin/phpunit tests/
```