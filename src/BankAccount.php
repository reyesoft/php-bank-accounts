<?php

declare(strict_types=1);

namespace BankAccounts;

/**
 * Abstract base class for bank account implementations.
 * Provides common functionality and enforces the BankAccountInterface.
 *
 * @codeCoverageIgnore
 */
abstract class BankAccount implements BankAccountInterface
{
    /**
     * Stores the raw bank account number or identifier.
     *
     * @var string The bank account number or identifier.
     */
    protected string $bankAccountNumber = '';

    /**
     * Gets the raw bank account number or identifier as provided to the constructor.
     *
     * @return string The bank account number or identifier.
     */
    public function getBankAccountNumber(): string
    {
        return $this->bankAccountNumber;
    }
}
