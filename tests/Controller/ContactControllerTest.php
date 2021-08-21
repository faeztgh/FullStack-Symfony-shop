<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testContact(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/contact/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'SAY SOMETHING NICE!');
        $this->assertSelectorTextSame('label', 'Full Name');

        $crawler = $client->request('GET', '/de/contact/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'SAGEN SIE ETWAS NETTES!');
        $this->assertSelectorTextSame('label', 'Vollständiger Name');
    }
}
