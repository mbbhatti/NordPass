<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin()
    {
        $authData = [
            'username' => 'john',
            'password' => 'maxsecure'
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($authData)
        );
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame($authData['username'], $content['username']);
    }

    public function testLogout()
    {
        $authData = [
            'username' => 'john',
            'password' => 'maxsecure'
        ];

        $client = static::createClient();
        $client->request(
            'POST',
            '/logout',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($authData)
        );

        $this->assertNotEmpty($client->getResponse()->getContent());
    }
}

