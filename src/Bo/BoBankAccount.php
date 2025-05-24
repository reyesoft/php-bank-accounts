<?php

declare(strict_types=1);

namespace BankAccounts\Bo;

use BankAccounts\BankAccount;
use BankAccounts\BankAccountInterface;

/**
 * Handles bank account validation and information for Bolivia (BO).
 * Supports CCI (Código de Cuenta Interbancaria) like format "XXX+YYYYYYYYYY".
 */
class BoBankAccount extends BankAccount implements BankAccountInterface
{
    /**
     * Constructor.
     * The Bolivian CCI-like format is expected as "AAA+000...000",
     * where AAA is the bank code and 000...000 is the account number.
     *
     * @param string $bankAccountNumber The Bolivian bank account identifier.
     */
    public function __construct(string $bankAccountNumber)
    {
        $this->bankAccountNumber = $bankAccountNumber;
    }

    /**
     * Gets the account identifier type.
     * For Bolivia, this is "CCI".
     *
     * @return string Returns "CCI".
     */
    public function getAccountTile(): string
    {
        return 'CCI';
    }

    /**
     * Validates the Bolivian bank account identifier (CCI-like format).
     * Expected format: 3 letters (bank code), a plus sign (+), and 10 to 22 digits (account number).
     *
     * @return bool True if the format is valid, false otherwise.
     */
    public function isValid(): bool
    {
        if (preg_match('/^([a-zA-Z]{3})\+([0-9]{10,22})$/', $this->bankAccountNumber) !== 1) {
            return false;
        }

        return true;
    }

    /**
     * Extracts the Bank ID from the Bolivian CCI-like identifier.
     * This is the first 3 letters before the '+'.
     *
     * @return string|null The Bank ID (3 letters), or null if the format is invalid.
     */
    public function getBankId(): ?string
    {
        if (preg_match('/^([a-zA-Z]{3})\+/', $this->bankAccountNumber, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Gets the bank name associated with the Bolivian CCI-like identifier.
     *
     * @return string|null The bank name, or null if not found or format is invalid.
     */
    public function getBankName(): ?string
    {
        $id = $this->getBankId();
        if ($id === null) {
            return null;
        }

        return BankNamesRepository::NAMES[$id] ?? null;
    }

    /**
     * Gets the internal bank account number part from the Bolivian CCI-like identifier.
     * This is the numeric part after the '+'.
     *
     * @return string|null The internal bank account number, or null if format is invalid.
     * @codeCoverageIgnore
     */
    public function getInternalBankAccountNumber(): ?string
    {
        if (preg_match('/^[a-zA-Z]{3}\+([0-9]{10,22})$/', $this->bankAccountNumber, $matches) === 1) {
            return $matches[1];
        }
        return null;
    }
}
