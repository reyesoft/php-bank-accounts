# Bank Accounts validator, names and more...

## Installation

Vía Composer

```bash
composer require reyesoft/bank-account
```

## Example

```php
use BankAccounts\Ar\ArBankAccount;
use BankAccounts\Mx\MxBankAccount;

$mx_bank = new MxBankAccount('072580010312850172');
$mx_bank->isValid(); // true
echo $mx_bank->getBankName(); // Banorte
echo $mx_bank->testGetInternalBankAccountNumber(); // 1031285017
echo $mx_bank->getAccountTile(); // CLABE

echo (new ArBankAccount('pablorsk.mp'))
    ->getAccountTile(); // Alias
echo (new ArBankAccount('0720321188000033530000'))
    ->getAccountTile(); // CBU/CVU
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