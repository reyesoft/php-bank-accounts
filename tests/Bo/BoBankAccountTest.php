<?php

declare(strict_types=1);
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace Tests\Bo;

use BankAccounts\Bo\BoBankAccount;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BankAccounts\Bo\BoBankAccount
 *
 * @internal
 */
final class BoBankAccountTest extends TestCase
{
    public function testIsValid(): void
    {
        static::assertFalse((new BoBankAccount(''))->isValid());
        static::assertFalse((new BoBankAccount('WQE+111111111'))->isValid());
        static::assertFalse((new BoBankAccount('AAAAA0000'))->isValid());
        static::assertFalse((new BoBankAccount('ASD+013409'))->isValid());
        static::assertFalse((new BoBankAccount('ASD+0105019465114-07'))->isValid());
        static::assertFalse((new BoBankAccount('ASD+0105019465119407 More text?'))->isValid());
        static::assertFalse((new BoBankAccount('ASD+12345678913423511616123'))->isValid());
        static::assertTrue((new BoBankAccount('ASD+1234567891342351161612'))->isValid());
        static::assertTrue((new BoBankAccount('ASD+2345678912'))->isValid());
    }

    public function testBankName(): void
    {
        static::assertNull((new BoBankAccount('00050194697194'))->getBankName());
    }

    public function testAccountTile(): void
    {
        static::assertSame('CCI', (new BoBankAccount('01050194697194'))->getAccountTile());
    }
}
