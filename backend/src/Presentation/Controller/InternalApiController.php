<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Domain\Contact\Repository\ContactLeadRepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/internal', name: 'api_internal_')]
final readonly class InternalApiController
{
    public function __construct(
        private ContactLeadRepositoryInterface $contactLeadRepository
    ) {
    }

    #[Route('/contacts/pending', name: 'contacts_pending', methods: ['GET'])]
    public function pending(\Symfony\Component\HttpFoundation\Request $request): JsonResponse
    {
        $limit = (int) $request->query->get('limit', '50');
        if ($limit < 1 || $limit > 100) {
            $limit = 50;
        }

        $contacts = $this->contactLeadRepository->findPending($limit);

        $data = array_map(fn ($c) => [
            'id' => $c->getId()->toString(),
            'name' => $c->getName(),
            'phone' => $c->getPhone()->toString(),
            'email' => $c->getEmail()?->toString(),
            'type' => $c->getType(),
            'area' => $c->getArea(),
            'message' => $c->getMessage(),
            'ip' => $c->getIp(),
            'userAgent' => $c->getUserAgent(),
            'createdAt' => $c->getCreatedAt()->toDateTimeImmutable()->format(\DateTimeInterface::ATOM),
        ], $contacts);

        return new JsonResponse(['data' => $data]);
    }

    #[Route('/contacts/{id}/sent', name: 'contacts_sent', methods: ['PATCH'])]
    public function markSent(string $id): JsonResponse
    {
        $contact = $this->contactLeadRepository->findById(Uuid::fromString($id));

        if ($contact === null) {
            return new JsonResponse(['error' => 'Contact not found'], Response::HTTP_NOT_FOUND);
        }

        $contact->markAsSent();
        $this->contactLeadRepository->save($contact);

        return new JsonResponse(['status' => 'ok']);
    }
}
