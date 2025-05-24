<?php

declare(strict_types=1);

namespace BankAccounts\Ar;

use BankAccounts\BankAccount;
use BankAccounts\BankAccountInterface;

/**
 * Handles bank account validation and information for Argentina (AR).
 * Supports CBU (Clave Bancaria Uniforme) and Alias.
 *
 * @author Pablo Gabriel Reyes
 *
 * @see https://pabloreyes.com.ar/ Blog
 * @see https://github.com/pablorsk/cbu-validator-php CBU validator on GitHub
 *
 * Based on Toba, de https://repositorio.siu.edu.ar/
 */
class ArBankAccount extends BankAccount implements BankAccountInterface
{
    /**
     * Constructor.
     *
     * @param string $cbu The CBU (Clave Bancaria Uniforme) or Alias.
     */
    public function __construct(string $cbu)
    {
        $this->bankAccountNumber = $cbu;
    }

    /**
     * Gets the account identifier type.
     * Returns "Alias" if the input appears to be an Alias, otherwise "CBU/CVU".
     *
     * @return string The account type ("Alias" or "CBU/CVU").
     */
    public function getAccountTile(): string
    {
        return $this->isAlias() ? 'Alias' : 'CBU/CVU';
    }

    /**
     * Checks if the stored bank account number is likely an Alias.
     * An Alias is assumed if it doesn't consist solely of digits.
     *
     * @return bool True if it's considered an Alias, false otherwise.
     */
    private function isAlias(): bool
    {
        return preg_match('/^[0-9]+$/', $this->bankAccountNumber) !== 1;
    }

    /**
     * Validates the Argentinian bank account identifier (CBU or Alias).
     *
     * @return bool True if the identifier is a valid CBU or a valid Alias, false otherwise.
     */
    public function isValid(): bool
    {
        if (preg_match('/^[0-9]+$/', $this->bankAccountNumber) === 1 && !$this->isValidCbu()) {
            return false;
        }

        return $this->isValidAlias();
    }

    /**
     * Validates an Argentinian Alias.
     * An Alias must be between 6 and 22 characters, consisting of letters, numbers, dots, and hyphens.
     * Based on BCRA communication B11478.
     *
     * @see http://www.bcra.gob.ar/Pdfs/comytexord/B11478.pdf
     *
     * @return bool True if the Alias format is valid, false otherwise.
     */
    public function isValidAlias(): bool
    {
        return preg_match('/^[A-Za-z.\-0-9]{6,22}$/', $this->bankAccountNumber) === 1;
    }

    /**
     * Validates an Argentinian CBU (Clave Bancaria Uniforme).
     * A CBU must be 22 digits long and pass check digit validation.
     * Based on Banco Central Argentino SNP3002.
     *
     * @see http://www.bcra.gov.ar/pdfs/snp/SNP3002.pdf
     *
     * @return bool True if the CBU is valid, false otherwise.
     */
    public function isValidCbu(): bool
    {
        // only 22 numbers
        if (preg_match('/[0-9]{22}/', $this->bankAccountNumber) !== 1) {
            return false;
        }
        /** @var array<int|string> $arr Note: str_split returns array of strings, but used as ints here. */
        $arr = str_split($this->bankAccountNumber);
        if (((int) $arr[7]) !== self::getDigitoVerificador($arr, 0, 6)) {
            // @codeCoverageIgnoreStart
            return false;
            // @codeCoverageIgnoreEnd
        }
        if (((int) $arr[21]) !== self::getDigitoVerificador($arr, 8, 20)) {
            return false;
        }

        return true;
    }

    /**
     * Calculates the verifier digit for a segment of the CBU.
     * Uses "clave 10 con ponderador 9713" algorithm.
     *
     * @param array<int|string> $numero Array of digits (as strings or ints).
     * @param int $posInicial Start position (inclusive) in $numero for calculation.
     * @param int $posFinal End position (inclusive) in $numero for calculation.
     *
     * @return int The calculated verifier digit.
     */
    private static function getDigitoVerificador(array $numero, int $posInicial, int $posFinal): int
    {
        $ponderador = [3, 1, 7, 9];
        $suma       = 0;
        $j          = 0;
        for ($i = $posFinal; $i >= $posInicial; --$i) {
            $suma = $suma + (((int) $numero[$i]) * $ponderador[$j % 4]);
            ++$j;
        }

        return (10 - $suma % 10) % 10;
    }

    /**
     * Extracts the Bank ID from the CBU.
     * For CBU, this is the first 3 digits. Not applicable for Alias.
     *
     * @return string|null The Bank ID if the input is a CBU, otherwise null.
     */
    public function getBankId(): ?string
    {
        if ($this->isAlias() || \strlen($this->bankAccountNumber) < 3) {
            return null;
        }

        return substr($this->bankAccountNumber, 0, 3);
    }

    /**
     * Gets the bank name associated with the CBU or a known Alias suffix.
     *
     * @return string|null The bank name, or null if not found or not applicable (e.g. generic Alias).
     */
    public function getBankName(): ?string
    {
        if (substr($this->bankAccountNumber, -5) === '.uala') {
            return 'Ualá';
        }

        if (substr($this->bankAccountNumber, -3) === '.mp') {
            return 'MercadoPago';
        }

        $id = $this->getBankId();
        if ($id === null) {
            return null;
        }

        return BankNamesRepository::NAMES[$id] ?? null;
    }

    /**
     * Gets the internal bank account number (CBU block 2).
     * For CBU, this is digits 9 to 21 (13 digits). Not applicable for Alias.
     *
     * @return string|null The internal bank account number if CBU, otherwise null.
     * @codeCoverageIgnore
     */
    public function getInternalBankAccountNumber(): ?string
    {
        if ($this->isAlias() || !$this->isValidCbu()) {
            return null;
        }
        // This method was previously returning null.
        // Correct CBU structure for internal account number is block 2 (digits 9-21).
        // Block 1: Bank (3) + Branch (4) + Check Digit 1 (1)
        // Block 2: Account (13) + Check Digit 2 (1)
        return substr($this->bankAccountNumber, 8, 13);
    }
}
