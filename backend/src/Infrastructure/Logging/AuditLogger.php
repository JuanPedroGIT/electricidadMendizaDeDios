<?php

declare(strict_types=1);

namespace App\Infrastructure\Logging;

use Psr\Log\LoggerInterface;

final readonly class AuditLogger
{
    public function __construct(
        private LoggerInterface $auditLogger
    ) {
    }

    public function log(
        string $action,
        string $entityType,
        ?string $entityId = null,
        ?string $userId = null,
        ?string $ip = null,
        array $context = []
    ): void {
        $this->auditLogger->info($action, [
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'user_id' => $userId,
            'ip' => $ip,
            'timestamp' => (new \DateTimeImmutable())->format('c'),
            'context' => $context,
        ]);
    }

    public function contactCreated(
        string $contactId,
        string $name,
        ?string $email = null,
        ?string $ip = null
    ): void {
        $this->log(
            'contact.created',
            'ContactLead',
            $contactId,
            null,
            $ip,
            ['name' => $name, 'email' => $email]
        );
    }

    public function adminLogin(
        bool $success,
        ?string $ip = null,
        ?string $error = null
    ): void {
        $this->log(
            $success ? 'admin.login.success' : 'admin.login.failed',
            'AdminSession',
            null,
            null,
            $ip,
            $success ? [] : ['error' => $error]
        );
    }

    public function adminLogout(?string $ip = null): void
    {
        $this->log('admin.logout', 'AdminSession', null, null, $ip);
    }

    public function serviceCreated(
        string $serviceId,
        string $name,
        string $slug,
        ?string $ip = null
    ): void {
        $this->log(
            'service.created',
            'Service',
            $serviceId,
            null,
            $ip,
            ['name' => $name, 'slug' => $slug]
        );
    }

    public function serviceUpdated(
        string $serviceId,
        string $name,
        ?string $ip = null
    ): void {
        $this->log(
            'service.updated',
            'Service',
            $serviceId,
            null,
            $ip,
            ['name' => $name]
        );
    }

    public function serviceDeleted(string $serviceId, ?string $ip = null): void
    {
        $this->log('service.deleted', 'Service', $serviceId, null, $ip);
    }

    public function faqCreated(
        string $faqId,
        string $question,
        ?string $ip = null
    ): void {
        $this->log(
            'faq.created',
            'Faq',
            $faqId,
            null,
            $ip,
            ['question' => $question]
        );
    }

    public function faqUpdated(
        string $faqId,
        string $question,
        ?string $ip = null
    ): void {
        $this->log(
            'faq.updated',
            'Faq',
            $faqId,
            null,
            $ip,
            ['question' => $question]
        );
    }

    public function faqDeleted(string $faqId, ?string $ip = null): void
    {
        $this->log('faq.deleted', 'Faq', $faqId, null, $ip);
    }

    public function settingsUpdated(?string $ip = null): void
    {
        $this->log('settings.updated', 'SiteSetting', null, null, $ip);
    }
}
