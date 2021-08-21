<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SearchControllerTest extends WebTestCase
{

    public function testSearchProducts()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/search?query=product2');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains("h2", "product2");
    }
}
