<?php

namespace App\Tests\Controller;

use App\Controller\RegistrationController;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{

    public function testRegister()
    {
        $client = static::createClient();

        //EN
        $crawler = $client->request('GET', '/register');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Register');
        $this->assertSelectorExists('label');
        $this->assertSelectorExists('input');
        $this->assertSelectorExists('button');
    }
}
