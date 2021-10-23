<?php

namespace App\Tests\Functional\Repository;

use App\Repository\ItemRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemRepositoryTest extends WebTestCase
{
    public function testGetAll()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);

        $itemRepository = static::$container->get(ItemRepository::class);
        $list = $itemRepository->getAll($user);

        $this->assertGreaterThan(0, $list);
    }

    public function testDeleteFail()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);

        $itemRepository = static::$container->get(ItemRepository::class);
        $item = $itemRepository->delete(0);

        $this->assertFalse($item);
    }

    public function testDeleteSuccess()
    {
        $client = static::createClient();

        $userRepository = static::$container->get(UserRepository::class);
        $user = $userRepository->findOneByUsername('john');
        $client->loginUser($user);

        $client->request('POST', '/item', ['data' => 'new item data']);
        $client->request('GET', '/item');
        $content = json_decode($client->getResponse()->getContent(), true);
        $lastRecord = end( $content );

        $itemRepository = static::$container->get(ItemRepository::class);
        $item = $itemRepository->delete($lastRecord['id']);

        $this->assertTrue($item);
    }
}

