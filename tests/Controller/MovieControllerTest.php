<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class MovieControllerTest extends WebTestCase
{
    public function testAdd()
    {
        // Create $client with a admin user 
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'gon@oclock.io',
            'PHP_AUTH_PW'   => 'groots',
        ]);
        $crawler = $client->request('POST', '/movie/add', [
            'body' => [
                'movie' => [
                    'title' => 'Kaamelott',
                    'releaseDate' => '2020-11-12',
                    'categories' => [],
                    'director' => 3,
                    'writers' => [],
                ]
            ]
        ]);
        $this->assertResponseRedirects();

    }
}

