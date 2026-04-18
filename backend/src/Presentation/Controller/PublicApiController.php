<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\Shared\Bus\CommandBusInterface;
use App\Application\Shared\Bus\QueryBusInterface;
use App\Application\Contact\Command\CreateContactLeadCommand;
use App\Application\Service\Query\GetServicesQuery;
use App\Application\Service\Query\GetServiceBySlugQuery;
use App\Application\Faq\Query\GetFaqsQuery;
use App\Application\SiteSetting\Query\GetPublicSiteSettingsQuery;
use App\Infrastructure\DataSeeder\DatabaseSeeder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\RateLimiter\RateLimiterFactory;

#[Route('/api', name: 'api_')]
final class PublicApiController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        private DatabaseSeeder $seeder,
        #[Autowire(service: 'limiter.contact_form')]
        private RateLimiterFactory $contactLimiter,
    ) {
    }

    #[Route('/services', name: 'services_list', methods: ['GET'])]
    public function services(): JsonResponse
    {
        $this->seeder->seedIfEmpty();

        $services = $this->queryBus->ask(new GetServicesQuery());

        return new JsonResponse(['data' => $services]);
    }

    #[Route('/services/{slug}', name: 'services_detail', methods: ['GET'])]
    public function serviceDetail(string $slug): JsonResponse
    {
        $this->seeder->seedIfEmpty();

        $service = $this->queryBus->ask(new GetServiceBySlugQuery($slug));

        if ($service === null) {
            return new JsonResponse(['error' => 'Service not found'], 404);
        }

        return new JsonResponse(['data' => $service]);
    }

    #[Route('/site-settings/public', name: 'site_settings_public', methods: ['GET'])]
    public function siteSettings(): JsonResponse
    {
        $this->seeder->seedIfEmpty();

        $settings = $this->queryBus->ask(new GetPublicSiteSettingsQuery());

        return new JsonResponse(['data' => $settings]);
    }

    #[Route('/faq', name: 'faq', methods: ['GET'])]
    public function faq(): JsonResponse
    {
        $this->seeder->seedIfEmpty();

        $faqs = $this->queryBus->ask(new GetFaqsQuery());

        return new JsonResponse(['data' => $faqs]);
    }

    #[Route('/contact', name: 'contact', methods: ['POST'])]
    public function contact(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true) ?? [];

        $key = $request->getClientIp() ?: 'anon';
        $limiter = $this->contactLimiter->create($key);
        $limit = $limiter->consume();
        if (!$limit->isAccepted()) {
            $retryAfter = $limit->getRetryAfter()?->getTimestamp();
            return new JsonResponse(
                ['error' => 'Too many requests. Inténtalo en unos minutos.'],
                Response::HTTP_TOO_MANY_REQUESTS,
                $retryAfter ? ['Retry-After' => $retryAfter] : []
            );
        }

        $command = new CreateContactLeadCommand(
            (string) ($payload['name'] ?? ''),
            (string) ($payload['phone'] ?? ''),
            $payload['email'] ?? null,
            $payload['type'] ?? null,
            $payload['area'] ?? null,
            $payload['message'] ?? null,
            $request->getClientIp(),
            $request->headers->get('User-Agent')
        );

        // Basic validation
        $errors = $this->validateContactPayload($command);
        if (!empty($errors)) {
            return new JsonResponse(['errors' => $errors], 422);
        }

        try {
            $this->commandBus->dispatch($command);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['errors' => ['validation' => $e->getMessage()]], 422);
        }

        return new JsonResponse(['status' => 'ok'], 201);
    }

    private function validateContactPayload(CreateContactLeadCommand $command): array
    {
        $errors = [];

        if (empty($command->name) || strlen($command->name) < 2) {
            $errors['name'] = 'Name must be at least 2 characters';
        }

        if (empty($command->phone) || !preg_match('/^[0-9+\s-]{6,20}$/', $command->phone)) {
            $errors['phone'] = 'Invalid phone number format';
        }

        if ($command->email !== null && !filter_var($command->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        return $errors;
    }
}
