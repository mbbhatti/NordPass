<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ItemRepository;
use App\Service\ItemService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\OptimisticLockException;

class ItemController extends AbstractController
{
    /**
     * @Route("/item", name="item_list", methods={"GET"})
     * @IsGranted("ROLE_USER")
     *
     * @param ItemRepository $itemRepository
     * @return JsonResponse
     */
    public function list(ItemRepository $itemRepository): JsonResponse
    {
        $items = $itemRepository->getAll($this->getUser());

        $allItems = [];
        foreach ($items as $item) {
            $oneItem['id'] = $item->getId();
            $oneItem['data'] = $item->getData();
            $oneItem['created_at'] = $item->getCreatedAt();
            $oneItem['updated_at'] = $item->getUpdatedAt();
            $allItems[] = $oneItem;
        }

        return $this->json($allItems);
    }

    /**
     * @Route("/item", name="item_create", methods={"POST"})
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ItemService $itemService
     * @return JsonResponse
     */
    public function create(Request $request, ItemService $itemService): JsonResponse
    {
        $data = $request->get('data');
        if (empty($data)) {
            return $this->json(['error' => 'No data parameter'], Response::HTTP_BAD_REQUEST);
        }

        $itemService->create($this->getUser(), $data);

        return $this->json([]);
    }

    /**
     * @Route("/item", name="item_update", methods={"PUT"})
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ItemService $itemService
     * @param ItemRepository $itemRepository
     * @return JsonResponse
     */
    public function update(Request $request, ItemService $itemService, ItemRepository $itemRepository): JsonResponse
    {
        $id = $request->get('id');
        if (empty($id)) {
            return $this->json(['error' => 'No id parameter'], Response::HTTP_BAD_REQUEST);
        }

        $item = $itemRepository->findOneById($id);
        if ($item === null) {
            return $this->json(['error' => 'No item found'], Response::HTTP_BAD_REQUEST);
        }

        $data = $request->get('data');
        if (empty($data)) {
            return $this->json(['error' => 'No data parameter'], Response::HTTP_BAD_REQUEST);
        }

        $itemService->update($item, $data);

        return $this->json([]);
    }

    /**
     * @Route("/item/{id}", name="items_delete", methods={"DELETE"})
     * @IsGranted("ROLE_USER")
     *
     * @param int $id
     * @param ItemRepository $itemRepository
     * @return JsonResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(int $id, ItemRepository $itemRepository): JsonResponse
    {
        if (empty($id)) {
            return $this->json(['error' => 'No id parameter'], Response::HTTP_BAD_REQUEST);
        }

        $item = $itemRepository->delete($id);
        if ($item === false) {
            return $this->json(['error' => 'No item found'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([]);
    }
}

