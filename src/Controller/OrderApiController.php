<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\OrderUpdateDTO;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order/api')]
final class OrderApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/create', name: 'order_api_new', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $order = $this->serializer->deserialize($request->getContent(), Order::class, 'json');
        $this->em->persist($order);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_CREATED);
    }

    #[Route('/list', name: 'order_api_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $order = $this->em->getRepository(Order::class)->findAll();

        $context = SerializationContext::create()->setGroups(['order_list']);
        $json = $this->serializer->serialize($order, 'json', $context);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'order_api_show', methods: ['GET'])]
    public function show(Order $order): JsonResponse
    {
        $context = SerializationContext::create()->setGroups(['order_detail']);
        $json = $this->serializer->serialize($order, 'json', $context);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}/update', name: 'order_api_update', methods: ['PUT'])]
    public function update(
        Request $request,
        Order $order
    ): JsonResponse {
        /** @var OrderUpdateDTO $orderUpdateDTO */
        $orderUpdateDTO = $this->serializer->deserialize(
            $request->getContent(),
            OrderUpdateDTO::class,
            'json'
        );

        $orderUpdateDTO->updateOrder($order);

        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'order_api_delete', methods: ['DELETE'])]
    public function delete(Order $order): JsonResponse
    {
        $this->em->remove($order);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
