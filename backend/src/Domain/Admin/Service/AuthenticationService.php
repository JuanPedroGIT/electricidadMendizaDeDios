<?php

declare(strict_types=1);

namespace App\Domain\Admin\Service;

use App\Domain\Admin\Entity\AdminSession;

final class AuthenticationService
{
    private string $adminPasswordHash;
    private int $sessionTtlSeconds;

    public function __construct(
        string $adminPasswordHash,
        int $sessionTtlSeconds = 7200
    ) {
        $this->adminPasswordHash = $adminPasswordHash;
        $this->sessionTtlSeconds = $sessionTtlSeconds;
    }

    public function authenticate(string $password, AdminSession $session): bool
    {
        if (!password_verify($password, $this->adminPasswordHash)) {
            return false;
        }

        $session->authenticate();
        return true;
    }

    public function validateSession(AdminSession $session): bool
    {
        if (!$session->isAuthenticated()) {
            return false;
        }

        if ($session->isExpired($this->sessionTtlSeconds)) {
            $session->logout();
            return false;
        }

        $session->refresh();
        return true;
    }

    public function validateCsrfToken(AdminSession $session, ?string $token): bool
    {
        return $session->isValidCsrfToken($token);
    }

    public function logout(AdminSession $session): void
    {
        $session->logout();
    }

    public function getSessionTtlSeconds(): int
    {
        return $this->sessionTtlSeconds;
    }
}
