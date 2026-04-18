<?php

declare(strict_types=1);

namespace App\Application\Service\Query;

use App\Application\Service\DTO\ServiceDTO;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Application\Shared\Query\QueryInterface;
use App\Domain\Service\Repository\ServiceRepositoryInterface;

final class GetServicesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ServiceRepositoryInterface $serviceRepository
    ) {
    }

    public function handle(QueryInterface $query): array
    {
        if (!$query instanceof GetServicesQuery) {
            throw new \InvalidArgumentException('Invalid query type');
        }

        $services = $this->serviceRepository->findAll();

        return array_map(fn ($service) => new ServiceDTO(
            $service->getId()->toString(),
            $service->getSlug(),
            $service->getName(),
            $service->getSummary(),
            $service->getBenefits()
        ), $services);
    }
}
