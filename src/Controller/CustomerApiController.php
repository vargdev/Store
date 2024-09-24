<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\CustomerUpdateDTO;
use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/customer/api')]
final class CustomerApiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/create', name: 'customer_api_new', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $customer = $this->serializer->deserialize($request->getContent(), Customer::class, 'json');
        $this->em->persist($customer);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_CREATED);
    }

    #[Route('/list', name: 'customer_api_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $customers = $this->em->getRepository(Customer::class)->findAll();

        $context = SerializationContext::create()->setGroups(['customer_list']);
        $json = $this->serializer->serialize($customers, 'json', $context);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'customer_api_show', methods: ['GET'])]
    public function show(Customer $customer): JsonResponse
    {
        $context = SerializationContext::create()->setGroups(['customer_detail']);
        $json = $this->serializer->serialize($customer, 'json', $context);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}/update', name: 'customer_api_update', methods: ['PUT'])]
    public function update(
        Request $request,
        Customer $customer
    ): JsonResponse {
        /** @var CustomerUpdateDTO $customerUpdateDTO */
        $customerUpdateDTO = $this->serializer->deserialize(
            $request->getContent(),
            CustomerUpdateDTO::class,
            'json'
        );

        $customerUpdateDTO->updateCustomer($customer);

        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'customer_api_delete', methods: ['DELETE'])]
    public function delete(Customer $customer): JsonResponse
    {
        $this->em->remove($customer);
        $this->em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
