<?php

declare(strict_types=1);

namespace App\Infrastructure\Health;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Contracts\Cache\CacheInterface;

final readonly class HealthCheckService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ?CacheInterface $redisCache = null,
        private ?TransportInterface $mailerTransport = null,
        private ?LoggerInterface $logger = null
    ) {
    }

    public function checkAll(): HealthStatus
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'redis' => $this->checkRedis(),
            'mailer' => $this->checkMailer(),
        ];

        $isHealthy = !in_array(false, array_column($checks, 'healthy'), true);

        return new HealthStatus(
            healthy: $isHealthy,
            checks: $checks,
            timestamp: new \DateTimeImmutable()
        );
    }

    private function checkDatabase(): array
    {
        $start = microtime(true);
        try {
            $this->entityManager->getConnection()->executeQuery('SELECT 1');
            return [
                'healthy' => true,
                'response_time_ms' => round((microtime(true) - $start) * 1000, 2),
            ];
        } catch (\Throwable $e) {
            $this->logger?->error('Health check failed: database', ['error' => $e->getMessage()]);
            return [
                'healthy' => false,
                'error' => 'Database connection failed',
                'response_time_ms' => round((microtime(true) - $start) * 1000, 2),
            ];
        }
    }

    private function checkRedis(): array
    {
        if ($this->redisCache === null) {
            return [
                'healthy' => null,
                'message' => 'Redis not configured',
            ];
        }

        $start = microtime(true);
        try {
            // Try to save and retrieve a test value
            $testKey = 'health_check_' . uniqid();
            $this->redisCache->get($testKey, function () { return 'ok'; });
            return [
                'healthy' => true,
                'response_time_ms' => round((microtime(true) - $start) * 1000, 2),
            ];
        } catch (\Throwable $e) {
            $this->logger?->error('Health check failed: redis', ['error' => $e->getMessage()]);
            return [
                'healthy' => false,
                'error' => 'Redis connection failed',
                'response_time_ms' => round((microtime(true) - $start) * 1000, 2),
            ];
        }
    }

    private function checkMailer(): array
    {
        if ($this->mailerTransport === null) {
            return [
                'healthy' => null,
                'message' => 'Mailer not configured',
            ];
        }

        $start = microtime(true);
        try {
            // Note: This only checks if the transport is configured, not if SMTP is reachable
            // For a real check, we could send a test email or ping the SMTP server
            return [
                'healthy' => true,
                'message' => 'Mailer configured',
                'response_time_ms' => round((microtime(true) - $start) * 1000, 2),
            ];
        } catch (\Throwable $e) {
            $this->logger?->error('Health check failed: mailer', ['error' => $e->getMessage()]);
            return [
                'healthy' => false,
                'error' => 'Mailer configuration error',
                'response_time_ms' => round((microtime(true) - $start) * 1000, 2),
            ];
        }
    }
}
