<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace BankAccounts\Ve;

use BankAccounts\BankAccount;
use BankAccounts\BankAccountInterface;

/**
 * @author Pablo Gabriel Reyes
 *
 * @see https://pabloreyes.com.ar/ Blog
 */
class VeBankAccount extends BankAccount implements BankAccountInterface
{
    public function __construct(string $cbu)
    {
        $this->bank_account_number = $cbu;
    }

    public function getAccountTile(): string
    {
        return 'Cuenta';
    }

    /**
     * By Banco Central Venezolano.
     */
    public function isValid(): bool
    {
        if (preg_match('/^[0-9]{20}$/', $this->bank_account_number) !== 1) {
            return false;
        }

        $entidad = substr($this->bank_account_number, 0, 4);
        $sucursal = substr($this->bank_account_number, 4, 4);
        $dc = substr($this->bank_account_number, 8, 2);
        $cuenta = substr($this->bank_account_number, 10);

        $dccalculado = self::getDigitoVerificador($entidad, $sucursal, $cuenta);
        if ($dccalculado == $dc) {
            return true;
        }

        return false;
    }

    /**
     * @param string $cuenta
     * @param string $entidad
     * @param string $sucursal
     */
    private static function getDigitoVerificador($entidad, $sucursal, $cuenta): string
    {
        $d = self::dc($entidad . $sucursal, false);
        $d .= self::dc($sucursal . $cuenta, true);

        return $d;
    }

    /**
     * @param string $numero
     * @param bool $escuenta
     */
    private static function dc($numero, $escuenta): int
    {
        if ($escuenta) {
            $pesos = [3, 2, 7, 6, 5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        } else {
            $pesos = [3, 2, 7, 6, 5, 4, 3, 2, 5, 4, 3, 2];
        }

        $s = 0;
        for ($i = 0; $i < strlen($numero); ++$i) {
            $d = $numero[$i];
            $s += intval($d) * $pesos[$i];
        }
        $resultado = (11 - ($s % 11));
        if ($resultado == 10) {
            /** @codeCoverageIgnoreStart */
            $resultado = 0;
        } elseif ($resultado == 11) {
            $resultado = 1;
            // @codeCoverageIgnoreEnd
        }

        return $resultado;
    }

    public function getBankId(): ?string
    {
        return substr($this->bank_account_number, 0, 4);
    }

    public function getBankName(): ?string
    {
        $id = self::getBankId();

        return BankNamesRepository::NAMES[$id] ?? null;
    }

    /** @codeCoverageIgnore */
    public function getInternalBankAccountNumber(): ?string
    {
        return null;
    }
}
