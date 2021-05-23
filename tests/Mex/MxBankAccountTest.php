<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace Tests\Mx;

use BankAccounts\Mx\MxBankAccount;
use PHPUnit\Framework\TestCase;

/**
 * @author Pablo Gabriel Reyes
 *
 * @covers \BankAccounts\Mx\MxBankAccount
 *
 * @internal
 */
final class MxBankAccountTest extends TestCase
{
    public function testIsValid()
    {
        static::assertFalse((new MxBankAccount(''))->isValid());
        static::assertFalse((new MxBankAccount('1231243457656434324325'))->isValid());
        static::assertFalse((new MxBankAccount('123124345765643432'))->isValid());
        static::assertTrue((new MxBankAccount('032180000118359719'))->isValid());
        static::assertTrue((new MxBankAccount('072580010312850172'))->isValid());
    }

    public function testBankName()
    {
        static::assertSame('IXE', (new MxBankAccount('032180000118359719'))->getBankName());
        static::assertSame('BANORTE', (new MxBankAccount('072580010312850172'))->getBankName());
        static::assertNull((new MxBankAccount('00050194697194012294'))->getBankName());
    }

    public function testGetInternalBankAccountNumber()
    {
        static::assertSame('1031285017', (new MxBankAccount('072580010312850172'))->getInternalBankAccountNumber());
        static::assertNull((new MxBankAccount('032180000118359719'))->getInternalBankAccountNumber());
    }
}