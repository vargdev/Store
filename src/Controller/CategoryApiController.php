<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\CategoryUpdateDTO;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category/api')]
final class CategoryApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/create', name: 'category_api_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $category = $this->serializer->deserialize($request->getContent(), Category::class, 'json');
        $this->em->persist($category);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_CREATED);
    }

    #[Route('/list', name: 'category_api_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $categories = $this->em->getRepository(Category::class)->findAll();

        $context = SerializationContext::create()->setGroups(['category_list']);
        $json = $this->serializer->serialize($categories, 'json', $context);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'category_api_show', methods: ['GET'])]
    public function show(Category $category): JsonResponse
    {
        $context = SerializationContext::create()->setGroups(['category_detail']);
        $json = $this->serializer->serialize($category, 'json', $context);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}/update', name: 'category_api_update', methods: ['PUT'])]
    public function update(
        Request $request,
        Category $category,
    ): JsonResponse {
        /** @var CategoryUpdateDTO $categoryUpdateDTO */
        $categoryUpdateDTO = $this->serializer->deserialize(
            $request->getContent(),
            CategoryUpdateDTO::class,
            'json'
        );

        $categoryUpdateDTO->updateCategory($category);

        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'category_api_delete', methods: ['DELETE'])]
    public function delete(Category $category): JsonResponse
    {
        $this->em->remove($category);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
