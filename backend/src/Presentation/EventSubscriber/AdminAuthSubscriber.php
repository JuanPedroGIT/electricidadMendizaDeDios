<?php

declare(strict_types=1);

namespace App\Presentation\EventSubscriber;

use App\Domain\Admin\Entity\AdminSession;
use App\Domain\Admin\Service\AuthenticationService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Simple session-based guard for admin API routes.
 * Requires a valid login and CSRF token on non-GET requests.
 * Sessions expire after 2 hours of inactivity.
 */
final class AdminAuthSubscriber implements EventSubscriberInterface
{
    public function __construct(
        #[Autowire('%env(ADMIN_PASSWORD_HASH)%')]
        private string $adminPasswordHash
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => ['onKernelRequest', 8],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $path = $request->getPathInfo();

        if (!str_starts_with($path, '/api/admin')) {
            return;
        }

        if ($path === '/api/admin/login') {
            return;
        }

        $session = $request->getSession();
        if (!$session?->get('is_admin')) {
            $event->setResponse(new JsonResponse(['error' => 'Unauthorized'], 401));
            return;
        }

        // Check session expiration (2 hours)
        $authService = new AuthenticationService($this->adminPasswordHash, 7200);
        $adminSession = new AdminSession(
            $session->get('is_admin'),
            $session->get('admin_logged_at'),
            $session->get('csrf_token')
        );

        if (!$authService->validateSession($adminSession)) {
            $session->clear();
            $event->setResponse(new JsonResponse(['error' => 'Session expired'], 401));
            return;
        }

        // Update session in request
        $session->set('admin_logged_at', $adminSession->getLoggedAt());

        if ($request->isMethodSafe(false)) {
            return;
        }

        $token = $request->headers->get('X-CSRF-TOKEN');
        if (!$token || $token !== $session->get('csrf_token')) {
            $event->setResponse(new JsonResponse(['error' => 'CSRF token invalid'], 403));
        }
    }
}
