<?php

declare(strict_types=1);

namespace App\Infrastructure\Health;

final readonly class HealthStatus
{
    /**
     * @param array<string, array<string, mixed>> $checks
     */
    public function __construct(
        public bool $healthy,
        public array $checks,
        public \DateTimeImmutable $timestamp
    ) {
    }

    public function toArray(): array
    {
        return [
            'status' => $this->healthy ? 'healthy' : 'unhealthy',
            'timestamp' => $this->timestamp->format('c'),
            'checks' => $this->checks,
        ];
    }
}
