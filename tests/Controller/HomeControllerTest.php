<?php

namespace App\Tests\Controller;

use App\Controller\HomeController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{

    public function testHome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame("p", "Faez Shop");
        $this->assertSelectorTextSame("h1", "Lux Products");
        self::assertSelectorExists('nav');
        self::assertSelectorExists('footer');

    }
}
