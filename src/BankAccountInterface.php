<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace BankAccounts;

interface BankAccountInterface
{
    public function __construct(string $bank_account_number);

    public function getAccountTile(): string;

    public function getBankAccountNumber(): string;

    public function isValid(): bool;

    public function getBankId(): ?string;

    public function getBankName(): ?string;

    public function getInternalBankAccountNumber(): ?string;
}
