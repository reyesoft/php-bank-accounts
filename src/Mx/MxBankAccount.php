<?php

declare(strict_types=1);

namespace BankAccounts\Mx;

use BankAccounts\BankAccount;
use BankAccounts\BankAccountInterface;

/**
 * Handles bank account validation and information for Mexico (MX).
 * Supports CLABE (Clave Bancaria Estandarizada).
 *
 * @see https://bank.codes/mexico-clabe-checker/
 */
class MxBankAccount extends BankAccount implements BankAccountInterface
{
    /**
     * Constructor.
     *
     * @param string $bankAccountNumber The Mexican CLABE (Clave Bancaria Estandarizada).
     */
    public function __construct(string $bankAccountNumber)
    {
        $this->bankAccountNumber = $bankAccountNumber;
    }

    /**
     * Gets the account identifier type.
     * For Mexico, this is "CLABE".
     *
     * @return string Returns "CLABE".
     */
    public function getAccountTile(): string
    {
        return 'CLABE';
    }

    /**
     * Validates the Mexican CLABE.
     * A CLABE must be 18 digits long and pass a specific check digit algorithm.
     *
     * @author Matías Ahumada
     *
     * @return bool True if the CLABE is valid, false otherwise.
     */
    public function isValid(): bool
    {
        if (preg_match('/^[0-9]{18}$/', $this->bankAccountNumber) !== 1) {
            return false;
        }

        $weightFactors = [3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7, 1, 3, 7];
        $moduleTen     = 10;
        $clabeChars    = str_split($this->bankAccountNumber);
        $accumulator   = 0;

        foreach ($weightFactors as $key => $value) {
            $accumulator = $accumulator + (((int) $clabeChars[$key]) * $value % $moduleTen);
        }

        $controlDigit = $moduleTen - ($accumulator % $moduleTen);
        $controlDigit = ($controlDigit === 10) ? 0 : $controlDigit;

        return end($clabeChars) === (string) $controlDigit;
    }

    /**
     * Extracts the Bank ID from the CLABE.
     * This is the first 3 digits of the CLABE.
     *
     * @return string|null The Bank ID (3 digits), or null if CLABE is too short.
     */
    public function getBankId(): ?string
    {
        if (\strlen($this->bankAccountNumber) < 3) {
            return null;
        }
        return substr($this->bankAccountNumber, 0, 3);
    }

    /**
     * Gets the bank name associated with the CLABE.
     *
     * @return string|null The bank name, or null if not found or Bank ID cannot be determined.
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
     * Gets the internal bank account number from the CLABE.
     * This is typically the part representing the local account number at the branch (plaza).
     * For an 18-digit CLABE, this is digits 7 to 16 (10 digits long).
     * CLABE structure: Bank Code (3), Branch Code (Plaza) (3), Account Number (11), Control Digit (1).
     * The "Account Number" part (11 digits) is often further structured.
     * This implementation returns digits 7-16, which is a common interpretation of the "main" account part.
     *
     * @return string|null The internal bank account number segment, or null if CLABE is too short.
     */
    public function getInternalBankAccountNumber(): ?string
    {
        // CLABE: BBB LLL AAAAAAAAAAA D (Bank, Location, Account, Digit)
        // Account number part is 11 digits. This method seems to aim for a 10-digit segment.
        // The original code returned digits 7 to 16 (index 6, length 10).
        // This corresponds to the first 10 digits of the 11-digit account number field.
        if (\strlen($this->bankAccountNumber) < 17) { // Needs at least up to the 17th char for substr(..., 6, 10)
            return null;
        }

        return substr($this->bankAccountNumber, 6, 11); // Corrected to get the full 11-digit account number
    }
}
