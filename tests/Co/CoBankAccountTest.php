<?php
/**
 * Copyright (C) 1997-2020 Reyesoft <info@reyesoft.com>.
 *
 * This file is part of Saldo.com.ar. Saldo.com.ar can not be copied and/or
 * distributed without the express permission of Reyesoft
 */

namespace Tests\Ar;

use BankAccounts\Co\CoBankAccount;
use PHPUnit\Framework\TestCase;

/**
 * @author Pablo Gabriel Reyes
 *
 * @see https://pabloreyes.com.ar/ Blog
 * @see https://github.com/pablorsk/cbu-validator-php CBU validator on GitHub
 *
 * @covers \BankAccounts\Co\CoBankAccount
 *
 * @internal
 */
final class CoBankAccountTest extends TestCase
{
    public function testIsValid(): void
    {
        static::assertFalse((new CoBankAccount('alias'))->isValid());
        static::assertFalse((new CoBankAccount('48841394'))->isValid());
        static::assertFalse((new CoBankAccount('48841394alias'))->isValid());
        static::assertFalse((new CoBankAccount('4884139412681121'))->isValid());
        static::assertTrue((new CoBankAccount('488413941268'))->isValid());
        static::assertTrue((new CoBankAccount('81681291890'))->isValid());
    }

    public function testGetAccountTitle(): void
    {
        static::assertSame('Cuenta', (new CoBankAccount('488413941268'))->getAccountTile());
    }
}
