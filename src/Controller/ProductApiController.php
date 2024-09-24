<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\ProductUpdateDTO;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/product/api')]
final class ProductApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/create', name: 'product_api_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $product = $this->serializer->deserialize($request->getContent(), Product::class, 'json');
        $this->em->persist($product);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_CREATED);
    }

    #[Route('/list', name: 'product_api_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $product = $this->em->getRepository(Product::class)->findAll();

        $context = SerializationContext::create()->setGroups(['product_list']);
        $json = $this->serializer->serialize($product, 'json', $context);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'product_api_show', methods: ['GET'])]
    public function show(Product $product): JsonResponse
    {
        $context = SerializationContext::create()->setGroups(['product_detail']);
        $json = $this->serializer->serialize($product, 'json', $context);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}/update', name: 'product_api_update', methods: ['PUT'])]
    public function update(
        Request $request,
        Product $product
    ): JsonResponse {
        /** @var ProductUpdateDTO $productUpdateDTO */
        $productUpdateDTO = $this->serializer->deserialize(
            $request->getContent(),
            ProductUpdateDTO::class,
            'json'
        );

        $productUpdateDTO->updateProduct($product);

        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'product_api_delete', methods: ['DELETE'])]
    public function delete(Product $product): JsonResponse
    {
        $this->em->remove($product);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
