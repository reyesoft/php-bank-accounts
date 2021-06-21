<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace BankAccounts\Mx;

use BankAccounts\BankAccount;
use BankAccounts\BankAccountInterface;

/**
 * @see https://bank.codes/mexico-clabe-checker/
 */
class MxBankAccount extends BankAccount implements BankAccountInterface
{
    public function __construct(string $clabe)
    {
        $this->bank_account_number = $clabe;
    }

    public function getAccountTile(): string
    {
        return 'CLABE';
    }

    /**
     * @author Matías Ahumada
     */
    public function isValid(): bool
    {
        if (!preg_match('/^[0-9]{18}$/', $this->bank_account_number)) {
            return false;
        }

        $WEIGHT_FACTORS = [3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7];
        $MODULE_TEN = 10;
        $clabeChars = str_split($this->bank_account_number);
        $accumulator = 0;

        foreach ($WEIGHT_FACTORS as $key => $value) {
            $accumulator = $accumulator + (((int) $clabeChars[$key]) * $value % $MODULE_TEN);
        }

        $controlDigit = $MODULE_TEN - ($accumulator % $MODULE_TEN);
        $controlDigit = $controlDigit === 10 ? 0 : $controlDigit;

        return end($clabeChars) === (string) $controlDigit;
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

    public function getInternalBankAccountNumber(): ?string
    {
        if (strlen($this->getBankAccountNumber()) < (17)) {
            return null;
        }

        return substr($this->getBankAccountNumber(), 6, 11);
    }
}
