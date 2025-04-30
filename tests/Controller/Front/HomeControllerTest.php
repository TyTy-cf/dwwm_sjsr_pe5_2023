<?php

namespace App\Tests\Controller\Front;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Instant-Faking');
    }

    public function testUserAdmin(): void
    {
        $client = static::createClient();

        // Log an user
        $user = $this->getUserRepository()->findOneBy(['email' => 'kevin@drosalys.fr']);
        $client->loginUser($user);

        $client->request('GET', '/');
        $this->assertSelectorExists('a[href="/admin"]');
    }

    public function testUserNotAdmin(): void
    {
        $client = static::createClient();

        // Log an user
        $user = $this->getUserRepository()->findOneBy(['email' => 'zprosacco@hotmail.com']);
        $client->loginUser($user);

        $client->request('GET', '/');
        $this->assertSelectorNotExists('a[href="/admin"]');
    }

    private function getUserRepository(): UserRepository {
        return $this->getContainer()->get(UserRepository::class);
    }

}
