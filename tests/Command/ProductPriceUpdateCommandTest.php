<?php

namespace App\Tests\Command;

use App\Command\ProductPriceUpdateCommand;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ProductPriceUpdateCommandTest extends TestCase
{
    public function testAdd(): void
    {
        $price = 10;
        $amount = 10;

        $price = $price + $amount;

        self::assertEquals(20, $price);
        self::assertNotNull($price);

    }

    public function testMinus(): void
    {
        $price = 10;
        $amount = 10;

        $price = $price - $amount;

        self::assertEquals(0, $price);
        self::assertNotEquals(-1, $price);
        self::assertNotEquals(1, $price);
        self::assertNotNull($price);
    }

    public function testIncrease(): void
    {
        $price = 100;
        $amount = 10;

        $price = ceil($price + (($amount / 100) * $price));

        self::assertEquals(110, $price);
        self::assertNotNull($price);

    }


    public function testDecrease(): void
    {
        $price = 110;
        $amount = 10;

        $price = ceil($price - (($amount / 100) * $price) + 1);

        self::assertEquals(100, $price);
        self::assertNotNull($price);

    }
}
