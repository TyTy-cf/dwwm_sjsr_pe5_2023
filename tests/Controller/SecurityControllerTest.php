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

    public function testLoginOK(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/se-connecter');

        // Get the form from the button "Se connecter"
        $form = $crawler->selectButton('Se connecter')->form();

        // Set the fields value ($form is an array, with key the input's name)
        $form['email'] = 'kevin@drosalys.fr';
        $form['password'] = '12345';

        // Submit the form
        $client->submit($form);

        // On login success => redirect to "home"
        $this->assertResponseRedirects('/');
    }

    public function testLoginKO(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/se-connecter');

        // Get the form from the button "Se connecter"
        $form = $crawler->selectButton('Se connecter')->form();

        // Set the fields value ($form is an array, with key the input's name)
        $form['email'] = 'kevin@drosalys.fr';
        $form['password'] = '1234';

        // Submit the form
        $client->submit($form);

        // On login success => redirect to "home"
        $this->assertResponseRedirects('/se-connecter');
    }
}
