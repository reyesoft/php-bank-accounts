<?php

declare(strict_types=1);

namespace BankAccounts\Ve;

use BankAccounts\BankAccount;
use BankAccounts\BankAccountInterface;

/**
 * Handles bank account validation and information for Venezuela (VE).
 * Venezuelan bank accounts are typically 20 digits long.
 *
 * @author Pablo Gabriel Reyes
 *
 * @see https://pabloreyes.com.ar/ Blog
 */
class VeBankAccount extends BankAccount implements BankAccountInterface
{
    /**
     * Constructor.
     *
     * @param string $bankAccountNumber The 20-digit Venezuelan bank account number.
     */
    public function __construct(string $bankAccountNumber)
    {
        $this->bankAccountNumber = $bankAccountNumber;
    }

    /**
     * Gets the account identifier type.
     * For Venezuela, this is "Cuenta".
     *
     * @return string Returns "Cuenta".
     */
    public function getAccountTile(): string
    {
        return 'Cuenta';
    }

    /**
     * Validates the 20-digit Venezuelan bank account number.
     * Validation includes checking the length and the control digits.
     * Based on information from Banco Central Venezolano.
     *
     * @return bool True if the account number is valid, false otherwise.
     */
    public function isValid(): bool
    {
        if (preg_match('/^[0-9]{20}$/', $this->bankAccountNumber) !== 1) {
            return false;
        }

        $entidad                = substr($this->bankAccountNumber, 0, 4);
        $sucursal               = substr($this->bankAccountNumber, 4, 4);
        $controlDigitsFromInput = substr($this->bankAccountNumber, 8, 2); // Control digits from input
        $cuenta                 = substr($this->bankAccountNumber, 10);   // Account's specific number

        $dccalculado = self::getDigitoVerificador($entidad, $sucursal, $cuenta);
        if ($dccalculado === $controlDigitsFromInput) {
            return true;
        }

        return false;
    }

    /**
     * Calculates the two control digits for a Venezuelan bank account number.
     *
     * @param string $entidad The 4-digit bank code.
     * @param string $sucursal The 4-digit branch code.
     * @param string $cuenta The 10-digit account specific number.
     *
     * @return string The two calculated control digits as a string.
     */
    private static function getDigitoVerificador(string $entidad, string $sucursal, string $cuenta): string
    {
        // First control digit: Calculated from bank code and first 4 digits of branch code.
        // Note: Original code used $entidad . $sucursal for first DC, which is 8 digits.
        // Standard usually involves specific parts. Assuming $entidad and $sucursal are correct as per usage.
        $dc1 = self::calculateControlDigit($entidad . $sucursal, false);

        // Second control digit: Calculated from branch code and account specific number.
        // Note: Original code used $sucursal . $cuenta, which is 14 digits.
        $dc2 = self::calculateControlDigit($sucursal . $cuenta, true);

        return \sprintf('%d%d', $dc1, $dc2);
    }

    /**
     * Calculates a single control digit based on a numeric string and weighting factors.
     *
     * @param string $numero The numeric string to calculate the digit for.
     * @param bool $isCuenta True if calculating for the account part (uses different weights), false for bank/branch part.
     *
     * @return int The calculated control digit (0-9).
     */
    private static function calculateControlDigit(string $numero, bool $isCuenta): int
    {
        $pesos = [3, 2, 7, 6, 5, 4, 3, 2, 7, 6, 5, 4, 3, 2]; // Default for $isCuenta = true (14 digits)
        if (!$isCuenta) {
            // Weights for the first DC (bank_code + branch_code, 8 digits originally)
            // This array is for 12 digits; the original code would effectively use the first 8.
            $pesos = [3, 2, 7, 6, 5, 4, 3, 2, 5, 4, 3, 2];
        }

        $sum       = 0;
        $numLength = \strlen($numero);
        for ($i = 0; $i < $numLength; ++$i) {
            $digit = $numero[$i];
            // Ensure pesos array is not accessed out of bounds if $numero is shorter than $pesos array
            if (isset($pesos[$i])) {
                $sum += ((int) $digit) * $pesos[$i];
            }
            // @codeCoverageIgnoreStart
            // This part remains as it was, assuming $numero length is usually handled,
            // or the specification implies shorter $numero just uses available $pesos.
            // @codeCoverageIgnoreEnd
        }

        $resultado = (11 - ($sum % 11));
        if ($resultado === 10) {
            /** @codeCoverageIgnoreStart */
            return 0;
            // @codeCoverageIgnoreEnd
        }
        if ($resultado === 11) {
            /** @codeCoverageIgnoreStart */
            return 1;
            // @codeCoverageIgnoreEnd
        }

        return $resultado;
    }

    /**
     * Extracts the Bank ID from the Venezuelan account number.
     * This is the first 4 digits.
     *
     * @return string|null The Bank ID (4 digits), or null if account number is too short.
     */
    public function getBankId(): ?string
    {
        if (\strlen($this->bankAccountNumber) < 4) {
            return null;
        }
        return substr($this->bankAccountNumber, 0, 4);
    }

    /**
     * Gets the bank name associated with the Venezuelan account number.
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
     * Gets the internal bank account number (the last 10 digits).
     * For a 20-digit Venezuelan account: BBBB SSSS DD CCCCCCCCCC
     * Returns the CCCCCCCCCC part.
     *
     * @return string|null The 10-digit internal account number, or null if account number is too short.
     * @codeCoverageIgnore
     */
    public function getInternalBankAccountNumber(): ?string
    {
        if (\strlen($this->bankAccountNumber) === 20) {
            return substr($this->bankAccountNumber, 10);
        }
        return null;
    }
}
