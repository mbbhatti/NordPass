<?php

namespace App\Service;

use App\Entity\Item;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ItemService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ItemService constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Create new item
     *
     * @param User $user
     * @param string $data
     */
    public function create(User $user, string $data): void
    {
        $item = new Item();
        $item->setUser($user);
        $item->setData($data);

        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }

    /**
     * Update item by id
     *
     * @param Item $item
     * @param string $data
     */
    public function update(Item $item, string $data): void
    {
        $item->setData($data);

        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }
}

