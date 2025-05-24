# Bank Accounts validator, names and more...

[![Build Status](https://github.com/pablorsk/php-bank-accounts/actions/workflows/php.yml/badge.svg)](https://github.com/pablorsk/php-bank-accounts/actions/workflows/php.yml)
[![Latest Stable Version](https://img.shields.io/packagist/v/reyesoft/bank-accounts.svg)](https://packagist.org/packages/reyesoft/bank-accounts)
[![Total Downloads](https://img.shields.io/packagist/dt/reyesoft/bank-accounts.svg)](https://packagist.org/packages/reyesoft/bank-accounts)
[![License](https://img.shields.io/packagist/l/reyesoft/bank-accounts.svg)](https://packagist.org/packages/reyesoft/bank-accounts)

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

## Coding Standards

This project follows the [PSR-12](https://www.php-fig.org/psr/psr-12/) coding style standard.
We use the following tools to ensure code quality and consistency:
- [PHP CS Fixer](https://cs.symfony.com/) for code formatting.
- [PHPStan](https://phpstan.org/) for static analysis.
- [PHPMD](https://phpmd.org/) for detecting potential problems in code.
- [PHPCPD](https://github.com/sebastianbergmann/phpcpd) for detecting duplicated code.

## Available countries

Based on [ISO alpha 2 characters](https://www.nationsonline.org/oneworld/country_code_list.htm).
* Argentina (AR)
* México (MX)
* Venezuela (VE)
* Colombia (CO)

## Development / Contributing

To contribute to this project, please follow these steps:

1.  **Fork and Clone:** Fork the repository and clone it to your local machine.
2.  **Install Dependencies:** Install development dependencies using Composer:
    ```bash
    composer install
    ```
3.  **Running Tests:** Execute the test suite using:
    ```bash
    composer test
    ```
4.  **Code Coverage:** To generate a code coverage report (make sure Xdebug or PCOV is enabled):
    ```bash
    composer coverage
    ```
5.  **Running Linters and Static Analysis (CI script):** To run all code quality checks as performed by the CI server:
    ```bash
    composer ci
    ```
    This command will run:
    - A script to find double spaces.
    - PHPStan for static analysis.
    - PHP CS Fixer in dry-run mode (to check formatting).
    - PHPMD for mess detection.
    - PHPCPD for copy/paste detection.

6.  **Applying Code Formatting:** To automatically fix coding style issues according to PHP CS Fixer rules:
    ```bash
    ./vendor/bin/php-cs-fixer fix --config=resources/rules/php-cs-fixer.php --allow-risky=yes
    ```
7.  **Branches and Pull Requests:** Create a new branch for your feature or bugfix. Once your changes are complete and all tests and CI checks pass, submit a pull request to the main repository.