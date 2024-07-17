<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace BankAccounts\Bo;

use BankAccounts\BankAccount;
use BankAccounts\BankAccountInterface;

class BoBankAccount extends BankAccount implements BankAccountInterface
{
    public function __construct(string $cci)
    {
        $this->bank_account_number = $cci;
    }

    public function getAccountTile(): string
    {
        return 'CCI';
    }

    public function isValid(): bool
    {
        if (preg_match('/^([a-zA-Z]{3})\+([0-9]{22})$/', $this->bank_account_number) !== 1) {
            return false;
        }

        return true;
    }

    public function getBankId(): ?string
    {
        return substr($this->bank_account_number, 0, 3);
    }

    public function getBankName(): ?string
    {
        $id = self::getBankId();

        return BankNamesRepository::NAMES[$id] ?? null;
    }

    /** @codeCoverageIgnore */
    public function getInternalBankAccountNumber(): ?string
    {
        return null;
    }
}
