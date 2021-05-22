<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace Tests\Ar;

use BankAccounts\Ar\ArBankAccount;
use PHPUnit\Framework\TestCase;

/**
 * This class doesn't do much yet..
 *
 * @author Pablo Gabriel Reyes
 *
 * @see https://pabloreyes.com.ar/ Blog
 * @see https://github.com/pablorsk/cbu-validator-php CBU validator on GitHub
 *
 * @version 1.0.0
 *
 * @covers \BankAccounts\Ar\ArBankAccount
 *
 * @internal
 */
final class ArBankAccountTest extends TestCase
{
    public function testIsValid()
    {
        static::assertFalse((new ArBankAccount('111111111'))->isValid());
        static::assertFalse((new ArBankAccount('AAAAA0000'))->isValid());
        static::assertFalse((new ArBankAccount('0720262188000036092117'))->isValid());
        static::assertTrue((new ArBankAccount('2850396540094708965758'))->isValid());
        static::assertTrue((new ArBankAccount('0720262188000036092118'))->isValid());
    }

    public function testBankName()
    {
        static::assertSame('Banco Santander Rio S.A.', (new ArBankAccount('0720321188000033530000'))->getBankName());
        static::assertNull((new ArBankAccount('0000321188000033530718'))->getBankName());
    }
}
