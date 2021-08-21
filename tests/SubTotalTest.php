<?php

namespace App\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class SubTotalTest extends TestCase
{
    public function testCalcSubtotal()
    {
        $cartItems = [
            [
                "price" => 100,
                "quantity" => 2
            ],
            [
                "price" => 100,
                "quantity" => 2
            ],
            [
                "price" => 100,
                "quantity" => 2
            ],
            [
                "price" => 100,
                "quantity" => 2
            ],
        ];

        $subtotal = null;
        self::assertNull($subtotal);

        foreach ($cartItems as $cartItem) {
            $subtotal += $cartItem["price"] * $cartItem["quantity"];
        }
        self::assertEquals(800, $subtotal);
        self::assertNotEquals(801, $subtotal);
        self::assertNotNull($subtotal);
    }
}
