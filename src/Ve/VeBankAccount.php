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
 * Permite validar los CBU (Clave Bancaria Uniforme), Argentina.
 *
 * @author Pablo Gabriel Reyes
 *
 * @see https://pabloreyes.com.ar/ Blog
 * @see https://github.com/pablorsk/cbu-validator-php CBU validator on GitHub
 *
 * @version 1.0.0
 *
 * Basado en Toba, de https://repositorio.siu.edu.ar/
 */
class VeBankAccount extends BankAccount implements BankAccountInterface
{
    public function __construct(string $cbu)
    {
        $this->bank_account_number = $cbu;
    }

    /**
     * By Banco Central Venezolano.
     */
    public function isValid(): bool
    {
        if (!preg_match('/^[0-9]{20}$/', $this->bank_account_number)) {
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

    private static function getDigitoVerificador($entidad, $sucursal, $cuenta)
    {
        $d = self::dc($entidad . $sucursal, false);
        $d .= self::dc($sucursal . $cuenta, true);

        return $d;
    }

    private static function dc($numero, $escuenta)
    {
        if ($escuenta) {
            $pesos = [3, 2, 7, 6, 5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        } else {
            $pesos = [3, 2, 7, 6, 5, 4, 3, 2, 5, 4, 3, 2];
        }

        $s = 0;
        for ($i = 0; $i < strlen($numero); ++$i) {
            $d = $numero[$i];
            $s += $d * $pesos[$i];
        }
        $resultado = (int) (11 - ($s % 11));
        if ($resultado == 10) {
            $resultado = 0;
        } elseif ($resultado == 11) {
            $resultado = 1;
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

    public function getInternalBankAccountNumber(): ?string
    {
        return null;
    }
}
