<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLoginSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/se-connecter');

        $this->assertResponseIsSuccessful();
    }

    public function testLoginFormWithUserName(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/se-connecter');

        $form = $crawler->selectButton('Se connecter')->form();
        $form['email'] = 'morgan93';
        $form['password'] = '12345';
        $client->submit($form);
    }

    public function testLoginAdmin(): void
    {
        $client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()
            ->get('doctrine')
            ->getRepository('App\Entity\User');

        $admin = $userRepository->findOneBy(['email' => 'kevin@drosalys.fr']);
        $client->loginUser($admin);

        $crawler = $client->request('GET', '/');
        $crawlerAdmin = $crawler->filter('a[href="/admin"]');

        $this->assertCount(1, $crawlerAdmin);
    }

    public function testLoginUser(): void
    {
        $client = static::createClient();
        /** @var UserRepository $userRepository */
        $userRepository = $this->getContainer()
            ->get('doctrine')
            ->getRepository('App\Entity\User');

        $user = $userRepository->findOneBy(['name' => 'morgan93']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/');
        $crawlerAdmin = $crawler->filter('a[href="/admin"]');

        $this->assertCount(0, $crawlerAdmin);
    }

}
