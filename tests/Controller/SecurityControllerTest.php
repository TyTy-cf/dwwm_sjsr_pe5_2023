<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/se-connecter');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Se connecter');
    }
}
