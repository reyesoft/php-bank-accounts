<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace BankAccounts\Ar;

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
class ArBankAccount extends BankAccount implements BankAccountInterface
{
    public function __construct(string $cbu)
    {
        $this->bank_account_number = $cbu;
    }

    /**
     * By Banco Central Argentino (http://www.bcra.gov.ar/pdfs/snp/SNP3002.pdf).
     */
    public function isValid(): bool
    {
        // only 22 numbers
        if (!preg_match('/[0-9]{22}/', $this->bank_account_number)) {
            return false;
        }
        $arr = str_split($this->bank_account_number);
        if ($arr[7] != self::getDigitoVerificador($arr, 0, 6)) {
            return false;
        }
        if ($arr[21] != self::getDigitoVerificador($arr, 8, 20)) {
            return false;
        }

        return true;
    }

    /**
     * Devuelve el dígito verificador para los dígitos de las posiciones "pos_inicial" a "pos_final"
     * de la cadena "$numero" usando clave 10 con ponderador 9713.
     *
     * @param array $numero arreglo de digitos
     * @param int $pos_inicial
     * @param int $pos_final
     *
     * @return int digito verificador de la cadena $numero
     */
    private static function getDigitoVerificador($numero, $pos_inicial, $pos_final): int
    {
        $ponderador = [3, 1, 7, 9];
        $suma = 0;
        $j = 0;
        for ($i = $pos_final; $i >= $pos_inicial; --$i) {
            $suma = $suma + ($numero[$i] * $ponderador[$j % 4]);
            ++$j;
        }

        return (10 - $suma % 10) % 10;
    }

    public function getBankId(): ?string
    {
        return substr($this->bank_account_number, 0, 3);
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
