<?php

declare(strict_types=1);

namespace App\Domain\Admin\Entity;

final class AdminSession
{
    private bool $isAuthenticated;
    private ?int $loggedAt;
    private ?string $csrfToken;

    public function __construct(
        bool $isAuthenticated = false,
        ?int $loggedAt = null,
        ?string $csrfToken = null
    ) {
        $this->isAuthenticated = $isAuthenticated;
        $this->loggedAt = $loggedAt;
        $this->csrfToken = $csrfToken;
    }

    public static function createEmpty(): self
    {
        return new self(false, null, null);
    }

    public static function createAuthenticated(string $csrfToken): self
    {
        return new self(true, time(), $csrfToken);
    }

    public function authenticate(): void
    {
        $this->isAuthenticated = true;
        $this->loggedAt = time();
        $this->csrfToken = bin2hex(random_bytes(24));
    }

    public function logout(): void
    {
        $this->isAuthenticated = false;
        $this->loggedAt = null;
        $this->csrfToken = null;
    }

    public function refresh(): void
    {
        if ($this->isAuthenticated) {
            $this->loggedAt = time();
        }
    }

    public function isAuthenticated(): bool
    {
        return $this->isAuthenticated;
    }

    public function getLoggedAt(): ?int
    {
        return $this->loggedAt;
    }

    public function getCsrfToken(): ?string
    {
        return $this->csrfToken;
    }

    public function isExpired(int $ttlSeconds): bool
    {
        if (!$this->isAuthenticated || $this->loggedAt === null) {
            return true;
        }

        return (time() - $this->loggedAt) > $ttlSeconds;
    }

    public function isValidCsrfToken(?string $token): bool
    {
        return $this->csrfToken !== null && $this->csrfToken === $token;
    }
}
