<?php

declare(strict_types=1);

namespace BankAccounts;

/**
 * Interface for bank account validation and information retrieval.
 * Defines the common methods that all country-specific bank account classes should implement.
 */
interface BankAccountInterface
{
    /**
     * Constructor.
     *
     * @param string $bankAccountNumber The bank account number or identifier (e.g., CBU, CLABE, Alias).
     */
    public function __construct(string $bankAccountNumber);

    /**
     * Gets a human-readable title or type for the bank account identifier.
     * For example, "CBU/CVU", "CLABE", "Alias", "Cuenta".
     *
     * @return string The title or type of the account identifier.
     */
    public function getAccountTile(): string;

    /**
     * Gets the raw bank account number or identifier as provided to the constructor.
     *
     * @return string The bank account number or identifier.
     */
    public function getBankAccountNumber(): string;

    /**
     * Validates the bank account number or identifier according to country-specific rules.
     *
     * @return bool True if the bank account identifier is valid, false otherwise.
     */
    public function isValid(): bool;

    /**
     * Gets the bank identifier code from the account number, if applicable.
     * This is typically a few digits that identify the financial institution.
     *
     * @return string|null The bank identifier code, or null if not applicable or cannot be determined.
     */
    public function getBankId(): ?string;

    /**
     * Gets the name of the bank associated with the account number.
     *
     * @return string|null The bank name, or null if not found or not applicable.
     */
    public function getBankName(): ?string;

    /**
     * Gets the internal (core) bank account number, often a segment of the full identifier.
     * This excludes bank codes, branch codes, or check digits, if applicable for the country.
     *
     * @return string|null The internal bank account number, or null if not applicable or cannot be determined.
     */
    public function getInternalBankAccountNumber(): ?string;
}
