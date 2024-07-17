<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace Tests\Bo;

use BankAccounts\Ar\ArBankAccount;
use BankAccounts\Bo\BoBankAccount;
use BankAccounts\Ve\VeBankAccount;
use PHPUnit\Framework\TestCase;

/**
 * @author Pablo Gabriel Reyes
 *
 * @see https://pabloreyes.com.ar/ Blog
 * @see https://github.com/pablorsk/cbu-validator-php CBU validator on GitHub
 *
 * @covers \BankAccounts\Ar\ArBankAccount
 *
 * @internal
 */
final class BoBankAccountTest extends TestCase
{
    public function testIsValid(): void
    {
        static::assertFalse((new BoBankAccount(''))->isValid());
        static::assertFalse((new BoBankAccount('111111111'))->isValid());
        static::assertFalse((new BoBankAccount('AAAAA0000'))->isValid());
        static::assertFalse((new BoBankAccount('01340946340001361695'))->isValid());
        static::assertFalse((new BoBankAccount('AAAAA0000'))->isValid());
        static::assertFalse((new BoBankAccount('01050194651194-079423'))->isValid());
        static::assertFalse((new BoBankAccount('01050194651194079423 More text?'))->isValid());
        static::assertFalse((new BoBankAccount('0720262188000036092117'))->isValid());
        static::assertTrue((new BoBankAccount('1234567891234567891234'))->isValid());
    }

    public function testBankName(): void
    {
        static::assertNull((new BoBankAccount('00050194697194012294'))->getBankName());
    }

    public function testAccountTile(): void
    {
        static::assertSame('CCI', (new BoBankAccount('01050194697194012294'))->getAccountTile());
    }
}
