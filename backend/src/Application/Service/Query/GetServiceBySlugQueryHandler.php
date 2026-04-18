<?php

declare(strict_types=1);

namespace App\Application\Service\Query;

use App\Application\Service\DTO\ServiceDTO;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Application\Shared\Query\QueryInterface;
use App\Domain\Service\Repository\ServiceRepositoryInterface;

final class GetServiceBySlugQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ServiceRepositoryInterface $serviceRepository
    ) {
    }

    public function handle(QueryInterface $query): ?ServiceDTO
    {
        if (!$query instanceof GetServiceBySlugQuery) {
            throw new \InvalidArgumentException('Invalid query type');
        }

        $service = $this->serviceRepository->findBySlug($query->slug);

        if ($service === null) {
            return null;
        }

        return new ServiceDTO(
            $service->getId()->toString(),
            $service->getSlug(),
            $service->getName(),
            $service->getSummary(),
            $service->getBenefits()
        );
    }
}
