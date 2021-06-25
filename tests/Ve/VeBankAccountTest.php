<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace Tests\Ve;

use BankAccounts\Ve\VeBankAccount;
use PHPUnit\Framework\TestCase;

/**
 * @author Pablo Gabriel Reyes
 *
 * @covers \BankAccounts\Ve\VeBankAccount
 *
 * @internal
 */
final class VeBankAccountTest extends TestCase
{
    public function testIsValid()
    {
        static::assertFalse((new VeBankAccount(''))->isValid());
        static::assertFalse((new VeBankAccount('111111111'))->isValid());
        static::assertFalse((new VeBankAccount('AAAAA0000'))->isValid());
        static::assertFalse((new VeBankAccount('01340946340001361695'))->isValid());
        static::assertFalse((new VeBankAccount('AAAAA0000'))->isValid());
        static::assertFalse((new VeBankAccount('01050194651194-079423'))->isValid());
        static::assertFalse((new VeBankAccount('01050194651194079423 More text?'))->isValid());
        static::assertFalse((new VeBankAccount('0720262188000036092117'))->isValid());
        static::assertTrue((new VeBankAccount('01050194651194079423'))->isValid());
    }

    public function testBankName()
    {
        static::assertSame('MERCANTIL', (new VeBankAccount('01050194697194012294'))->getBankName());
        static::assertNull((new VeBankAccount('00050194697194012294'))->getBankName());
    }

    public function testAccountTile()
    {
        static::assertSame('Cuenta', (new VeBankAccount('01050194697194012294'))->getAccountTile());
    }
}
