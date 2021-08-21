<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class LoginControllerTest extends WebTestCase
{

    public function testLogin(): void
    {
        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', 'http://localhost:8000/login');
        $browser->submitForm('Login', ['username' => 'user', 'password' => '123456']);
        $home = $browser->getHistory()->current()->getUri();

        $client = static::createClient();
        self::assertEquals('http://localhost:8000/', $home);
    }


    public function testLoginPage(): void
    {
        $client = static::createClient();

        //EN
        $crawler = $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Login');
        $this->assertSelectorTextSame('label', 'Username');
        $this->assertSelectorExists('label');
        $this->assertSelectorExists('button');
        $this->assertSelectorExists('a');

        //DE
        $crawler = $client->request('GET', '/de/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Anmeldung');
        $this->assertSelectorTextSame('label', 'Benutzername');
    }
}
