<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ResetPasswordControllerTest extends WebTestCase
{
    public function testResetPassword(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/reset-password');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Reset your password');
        $this->assertSelectorTextSame('label', 'Email');
        $this->assertSelectorExists('input');
        $this->assertSelectorExists('button');
    }
}
