<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace BankAccounts;

/** @codeCoverageIgnore */
abstract class BankAccount implements BankAccountInterface
{
    /**
     * @var string
     */
    protected $bank_account_number = '';

    public function getBankAccountNumber(): string
    {
        return $this->bank_account_number;
    }
}
