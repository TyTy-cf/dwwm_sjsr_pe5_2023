<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{

    public function testSuccess(): void
    {
        $client = static::createClient(); // Simule un navigateur
        $client->request('GET', '/'); // Lance une requête GET sur la home

        $this->assertResponseIsSuccessful('Something went wrong when trying to reach "/"');
    }

    public function testHeadTitle(): void
    {
        $client = static::createClient(); // Simule un navigateur
        $client->request('GET', '/'); // Lance une requête GET sur la home

        $this->assertSelectorTextContains('h1', 'Instant-Faking');
    }

    public function testHomePageSubtitle()
    {
        $client = static::createClient();
        $crawlerGlobal = $client->request('GET', '/');
        $crawler = $crawlerGlobal->filter('h2');

        $this->assertCount(3, $crawler, "One title is missing");
    }

}
