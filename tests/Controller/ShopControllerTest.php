<?php

namespace App\Tests\Controller;

use App\Controller\ShopController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShopControllerTest extends WebTestCase
{

    public function testShow()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/shop/11');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'product1');
        $this->assertSelectorTextSame('h4', 'Austen');
        $this->assertSelectorExists('button', 'Add To Cart');
    }

    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/shop/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h2', 'product1');
        $this->assertSelectorExists('button', 'Add To Cart');

    }
}
