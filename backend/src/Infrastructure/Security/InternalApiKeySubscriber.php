<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class InternalApiKeySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private string $internalApiKey
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 10],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        if (!str_starts_with($path, '/api/internal')) {
            return;
        }

        $providedKey = $request->headers->get('X-Internal-Api-Key');

        if ($providedKey !== $this->internalApiKey) {
            $event->setResponse(new JsonResponse(
                ['error' => 'Unauthorized'],
                JsonResponse::HTTP_UNAUTHORIZED
            ));
        }
    }
}
