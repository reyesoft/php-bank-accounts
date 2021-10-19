<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace BankAccounts\Co;

use BankAccounts\BankAccount;
use BankAccounts\BankAccountInterface;

/**
 * @author Pablo Gabriel Reyes
 *
 * @see https://pabloreyes.com.ar/ Blog
 */
class CoBankAccount extends BankAccount implements BankAccountInterface
{
    public function __construct(string $bank_account_number)
    {
        $this->bank_account_number = $bank_account_number;
    }

    public function getAccountTile(): string
    {
        return 'Cuenta';
    }

    public function isValid(): bool
    {
        if (preg_match('/^[0-9]{11,12}$/', $this->bank_account_number) !== 1) {
            return false;
        }

        return true;
    }

    /** @codeCoverageIgnore */
    public function getBankId(): ?string
    {
        return '';
    }

    /** @codeCoverageIgnore */
    public function getBankName(): ?string
    {
        return null;
    }

    /** @codeCoverageIgnore */
    public function getInternalBankAccountNumber(): ?string
    {
        return null;
    }
}
