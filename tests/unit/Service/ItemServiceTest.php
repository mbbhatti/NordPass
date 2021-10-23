<?php

namespace App\Tests\Unit;

use App\Entity\Item;
use App\Entity\User;
use App\Repository\ItemRepository;
use App\Service\ItemService;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ItemServiceTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface|MockObject
     */
    private $entityManager;

    /**
     * @var ItemService
     */
    private $itemService;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->itemService = new ItemService($this->entityManager);
    }

    public function testCreate()
    {
        $user = $this->createMock(User::class);
        $data = 'create data';

        $expectedObject = new Item();
        $expectedObject->setUser($user);
        $expectedObject->setData($data);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($expectedObject);

        $this->itemService->create($user, $data);
    }

    public function testUpdate()
    {
        $user = $this->createMock(User::class);
        $data = 'update data';

        $expectedObject = new Item();
        $expectedObject->setUser($user);
        $expectedObject->setData($data);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($expectedObject);

        $this->itemService->update($expectedObject, $data);
    }

    protected function tearDown(): void
    {
        $this->entityManager->close();
        $this->entityManager = null;
    }
}

