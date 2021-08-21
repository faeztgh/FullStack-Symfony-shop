<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AboutControllerTest extends WebTestCase
{
    public function testAbout(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/about/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'About Us');
        self::assertSelectorTextNotContains('h1', 'Über uns');

        $crawler = $client->request('GET', '/de/about/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Über uns');
        self::assertSelectorTextNotContains('h1', 'About Us');

    }
}
