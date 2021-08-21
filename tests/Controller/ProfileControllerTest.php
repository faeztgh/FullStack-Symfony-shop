<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProfileControllerTest extends WebTestCase
{
    public function testProfile(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/profile');

        $this->assertResponseRedirects('/login',302);
    }
}
