<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/ticket/5');
        $this->assertResponseRedirects('/login',302);

        $crawler = $client->request('GET', '/ticket/5/edit');
        $this->assertResponseRedirects('/login',302);

    }
}
