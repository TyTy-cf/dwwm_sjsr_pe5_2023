<?php

namespace App\Tests\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/panier');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Mon panier');
    }
}
