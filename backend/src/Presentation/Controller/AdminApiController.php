<?php

declare(strict_types=1);

namespace App\Presentation\Controller;

use App\Application\Shared\Bus\CommandBusInterface;
use App\Application\Shared\Bus\QueryBusInterface;
use App\Application\Admin\Command\LoginAdminCommand;
use App\Application\Admin\Command\LogoutAdminCommand;
use App\Application\Service\Query\GetServicesQuery;
use App\Application\Service\Command\CreateServiceCommand;
use App\Application\Service\Command\UpdateServiceCommand;
use App\Application\Service\Command\DeleteServiceCommand;
use App\Application\Faq\Query\GetFaqsQuery;
use App\Application\Faq\Command\CreateFaqCommand;
use App\Application\Faq\Command\UpdateFaqCommand;
use App\Application\Faq\Command\DeleteFaqCommand;
use App\Application\SiteSetting\Query\GetPublicSiteSettingsQuery;
use App\Application\SiteSetting\Command\UpdateSiteSettingsCommand;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\RateLimiter\RateLimiterFactory;

#[Route('/api/admin', name: 'api_admin_')]
final class AdminApiController
{
    public function __construct(
        private CommandBusInterface $commandBus,
        private QueryBusInterface $queryBus,
        #[Autowire(service: 'limiter.login')]
        private RateLimiterFactory $loginLimiter,
    ) {
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true) ?? [];
        $password = (string) ($payload['password'] ?? '');

        $limiter = $this->loginLimiter->create($request->getClientIp() ?: 'anon');
        $limit = $limiter->consume();
        if (!$limit->isAccepted()) {
            return new JsonResponse(['error' => 'Too many attempts'], 429);
        }

        $command = new LoginAdminCommand(
            $password,
            $request->getClientIp() ?: 'anon'
        );

        try {
            $session = $this->commandBus->dispatch($command);

            $request->getSession()->set('is_admin', $session->isAuthenticated());
            $request->getSession()->set('admin_logged_at', $session->getLoggedAt());
            $request->getSession()->set('csrf_token', $session->getCsrfToken());

            return new JsonResponse(['status' => 'ok', 'csrf_token' => $session->getCsrfToken()]);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => 'Invalid credentials'], 401);
        }
    }

    #[Route('/logout', name: 'logout', methods: ['POST'])]
    public function logout(Request $request): JsonResponse
    {
        $command = new LogoutAdminCommand();
        $this->commandBus->dispatch($command);

        $request->getSession()->clear();

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/services', name: 'services_list', methods: ['GET'])]
    public function services(): JsonResponse
    {
        $services = $this->queryBus->ask(new GetServicesQuery());
        return new JsonResponse(['data' => $services]);
    }

    #[Route('/services', name: 'services_create', methods: ['POST'])]
    public function createService(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true) ?? [];
        $errors = $this->validateServicePayload($payload);
        if ($errors) {
            return new JsonResponse(['errors' => $errors], 422);
        }

        $command = new CreateServiceCommand(
            $payload['slug'],
            $payload['name'],
            $payload['summary'],
            $payload['benefits']
        );

        try {
            $id = $this->commandBus->dispatch($command);
            return new JsonResponse(['data' => ['id' => $id]], 201);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['errors' => ['slug' => $e->getMessage()]], 422);
        }
    }

    #[Route('/services/{id}', name: 'services_update', methods: ['PUT'])]
    public function updateService(string $id, Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true) ?? [];
        $errors = $this->validateServicePayload($payload);
        if ($errors) {
            return new JsonResponse(['errors' => $errors], 422);
        }

        $command = new UpdateServiceCommand(
            $id,
            $payload['slug'],
            $payload['name'],
            $payload['summary'],
            $payload['benefits']
        );

        try {
            $this->commandBus->dispatch($command);
            return new JsonResponse(['status' => 'ok']);
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'Service not found') {
                return new JsonResponse(['error' => 'No encontrado'], 404);
            }
            return new JsonResponse(['errors' => ['slug' => $e->getMessage()]], 422);
        }
    }

    #[Route('/faq', name: 'faq_list', methods: ['GET'])]
    public function faq(): JsonResponse
    {
        $faqs = $this->queryBus->ask(new GetFaqsQuery());
        return new JsonResponse(['data' => $faqs]);
    }

    #[Route('/faq', name: 'faq_create', methods: ['POST'])]
    public function createFaq(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true) ?? [];
        $errors = $this->validateFaqPayload($payload);
        if ($errors) {
            return new JsonResponse(['errors' => $errors], 422);
        }

        $command = new CreateFaqCommand(
            $payload['question'],
            $payload['answer']
        );

        $id = $this->commandBus->dispatch($command);
        return new JsonResponse(['data' => ['id' => $id]], 201);
    }

    #[Route('/faq/{id}', name: 'faq_update', methods: ['PUT'])]
    public function updateFaq(string $id, Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true) ?? [];
        $errors = $this->validateFaqPayload($payload);
        if ($errors) {
            return new JsonResponse(['errors' => $errors], 422);
        }

        $command = new UpdateFaqCommand(
            $id,
            $payload['question'],
            $payload['answer']
        );

        try {
            $this->commandBus->dispatch($command);
            return new JsonResponse(['status' => 'ok']);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => 'No encontrado'], 404);
        }
    }

    #[Route('/services/{id}', name: 'services_delete', methods: ['DELETE'])]
    public function deleteService(string $id): JsonResponse
    {
        $command = new DeleteServiceCommand($id);

        try {
            $this->commandBus->dispatch($command);
            return new JsonResponse(['status' => 'ok']);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => 'No encontrado'], 404);
        }
    }

    #[Route('/faq/{id}', name: 'faq_delete', methods: ['DELETE'])]
    public function deleteFaq(string $id): JsonResponse
    {
        $command = new DeleteFaqCommand($id);

        try {
            $this->commandBus->dispatch($command);
            return new JsonResponse(['status' => 'ok']);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['error' => 'No encontrado'], 404);
        }
    }

    #[Route('/site-settings', name: 'site_settings_get', methods: ['GET'])]
    public function siteSettings(): JsonResponse
    {
        $settings = $this->queryBus->ask(new GetPublicSiteSettingsQuery());
        return new JsonResponse(['data' => $settings]);
    }

    #[Route('/site-settings', name: 'site_settings_update', methods: ['PUT'])]
    public function updateSiteSettings(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '{}', true) ?? [];
        $errors = $this->validateSiteSettingsPayload($payload);
        if ($errors) {
            return new JsonResponse(['errors' => $errors], 422);
        }

        $command = new UpdateSiteSettingsCommand($payload);
        $this->commandBus->dispatch($command);

        return new JsonResponse(['status' => 'ok']);
    }

    private function validateServicePayload(array $payload): array
    {
        $errors = [];
        if (empty($payload['slug']) || !is_string($payload['slug'])) {
            $errors['slug'] = 'Slug requerido';
        }
        if (empty($payload['name']) || !is_string($payload['name'])) {
            $errors['name'] = 'Nombre requerido';
        }
        if (empty($payload['summary']) || !is_string($payload['summary'])) {
            $errors['summary'] = 'Resumen requerido';
        }
        if (!isset($payload['benefits']) || !is_array($payload['benefits']) || count($payload['benefits']) === 0) {
            $errors['benefits'] = 'Lista de beneficios requerida';
        }

        return $errors;
    }

    private function validateFaqPayload(array $payload): array
    {
        $errors = [];
        if (empty($payload['question']) || !is_string($payload['question'])) {
            $errors['question'] = 'Pregunta requerida';
        }
        if (empty($payload['answer']) || !is_string($payload['answer'])) {
            $errors['answer'] = 'Respuesta requerida';
        }

        return $errors;
    }

    private function validateSiteSettingsPayload(array $payload): array
    {
        $required = ['brand', 'tagline', 'phone', 'phone_display', 'whatsapp', 'email', 'address', 'cta'];
        $errors = [];
        foreach ($required as $field) {
            if (!isset($payload[$field]) || !is_string($payload[$field]) || $payload[$field] === '') {
                $errors[$field] = 'Campo requerido';
            }
        }

        return $errors;
    }
}
