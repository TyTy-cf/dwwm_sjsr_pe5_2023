<?php

namespace App\Tests\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testSuccessful(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/inscription');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Inscription');
    }

    public function testFormExists(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/inscription');

        $this->assertSelectorExists('form[name="user"]', 'Failed to find the form with name "user"');
    }
}
