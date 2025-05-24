<?php

declare(strict_types=1);

namespace BankAccounts\Co;

use BankAccounts\BankAccount;
use BankAccounts\BankAccountInterface;

/**
 * Handles bank account validation and information for Colombia (CO).
 *
 * @author Pablo Gabriel Reyes
 *
 * @see https://pabloreyes.com.ar/ Blog
 */
class CoBankAccount extends BankAccount implements BankAccountInterface
{
    /**
     * Constructor.
     *
     * @param string $bankAccountNumber The Colombian bank account number.
     */
    public function __construct(string $bankAccountNumber)
    {
        $this->bankAccountNumber = $bankAccountNumber;
    }

    /**
     * Gets the account identifier type.
     * For Colombia, this is "Cuenta".
     *
     * @return string Returns "Cuenta".
     */
    public function getAccountTile(): string
    {
        return 'Cuenta';
    }

    /**
     * Validates the Colombian bank account number.
     * It must be between 10 and 12 digits long.
     *
     * @return bool True if the account number format is valid, false otherwise.
     */
    public function isValid(): bool
    {
        if (preg_match('/^[0-9]{10,12}$/', $this->bankAccountNumber) !== 1) {
            return false;
        }

        return true;
    }

    /**
     * Gets the Bank ID for a Colombian bank account.
     * Currently, this method is not implemented and returns null.
     *
     * @return string|null Always returns null as it's not implemented.
     * @codeCoverageIgnore
     */
    public function getBankId(): ?string
    {
        // Implementation for Colombian Bank ID extraction is not available.
        return null;
    }

    /**
     * Gets the Bank Name for a Colombian bank account.
     * Currently, this method is not implemented and returns null as Bank ID extraction is unavailable.
     *
     * @return string|null Always returns null as it's not implemented.
     * @codeCoverageIgnore
     */
    public function getBankName(): ?string
    {
        // Bank name retrieval depends on Bank ID, which is not implemented.
        return null;
    }

    /**
     * Gets the internal bank account number for a Colombian bank account.
     * Currently, this method is not implemented and returns null.
     *
     * @return string|null Always returns null as it's not implemented.
     * @codeCoverageIgnore
     */
    public function getInternalBankAccountNumber(): ?string
    {
        // Implementation for internal account number extraction is not available.
        return null;
    }
}
