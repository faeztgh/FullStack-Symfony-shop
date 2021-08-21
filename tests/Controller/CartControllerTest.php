<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public function testCart(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/cart');
        $this->assertResponseRedirects('/login',302);

    }
}
