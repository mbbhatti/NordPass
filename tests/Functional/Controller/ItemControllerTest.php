<?php

namespace App\Tests\Functional\Controller;

use App\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Repository\UserRepository;

class ItemControllerTest extends WebTestCase
{
    public function testCreate()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $itemRepository = static::$container->get(ItemRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);

        $data = 'very secure new item data';
        $newItemData = ['data' => $data];

        $client->request('POST', '/item', $newItemData);
        $client->request('GET', '/item');

        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('very secure new item data', $client->getResponse()->getContent());

        $itemRepository->findOneByData($data);
    }

    public function testGet()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);

        $client->request('GET', '/item');
        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertGreaterThan(0, $content);
    }

    public function testUpdateSuccess()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);

        $client->request('GET', '/item');
        $content = json_decode($client->getResponse()->getContent(), true);
        $lastRecord = end( $content );
        $id = $lastRecord['id'];
        $data = 'updated item data';
        $updateItemData = [
            'id' => $id,
            'data' => $data
        ];

        $client->request('PUT', '/item', $updateItemData);
        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertCount(0, $content);

        $itemRepository = static::$container->get(ItemRepository::class);
        $response = $itemRepository->find($lastRecord['id']);

        $this->assertSame($response->getData(), $data);
    }

    public function testUpdateEmptyId()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);

        $updateItemData = [
            'id' => '',
            'data' => 'updated item data'
        ];

        $client->request('PUT', '/item', $updateItemData);
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('No id parameter', $content['error']);
    }

    public function testUpdateWrongId()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);

        $updateItemData = [
            'id' => 123456789,
            'data' => 'updated item data'
        ];

        $client->request('PUT', '/item', $updateItemData);
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('No item found', $content['error']);
    }

    public function testUpdateEmptyData()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);

        $client->request('GET', '/item');
        $content = json_decode($client->getResponse()->getContent(), true);
        $lastRecord = end( $content );
        $id = $lastRecord['id'];

        $updateItemData = [
            'id' => $id,
            'data' => ''
        ];

        $client->request('PUT', '/item', $updateItemData);
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame('No data parameter', $content['error']);
    }

    public function testDeleteSuccess()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);

        $client->request('GET', '/item');
        $content = json_decode($client->getResponse()->getContent(), true);
        $lastRecord = end( $content );
        $id = $lastRecord['id'];

        $client->request('Delete', '/item/' . $id);
        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertCount(0, $content);
    }

    public function testDeleteFail()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);

        $client->request('Delete', '/item/0');
        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertSame('No id parameter', $content['error']);
    }
}

