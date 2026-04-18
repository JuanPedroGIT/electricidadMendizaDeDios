<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Infrastructure\Health\HealthCheckService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class HealthController
{
    public function __construct(
        private HealthCheckService $healthCheckService
    ) {
    }

    #[Route('/health', name: 'health', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $status = $this->healthCheckService->checkAll();

        return new JsonResponse(
            $status->toArray(),
            $status->healthy ? 200 : 503
        );
    }
}
