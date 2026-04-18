<?php

declare(strict_types=1);

namespace App\Application\Admin\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Command\CommandInterface;
use App\Domain\Admin\Entity\AdminSession;
use App\Domain\Admin\Service\AuthenticationService;
use App\Infrastructure\Logging\AuditLogger;

final class LoginAdminCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private AuthenticationService $authenticationService,
        private AuditLogger $auditLogger
    ) {
    }

    public function handle(CommandInterface $command): AdminSession
    {
        if (!$command instanceof LoginAdminCommand) {
            throw new \InvalidArgumentException('Invalid command type');
        }

        $session = AdminSession::createEmpty();

        if (!$this->authenticationService->authenticate($command->password, $session)) {
            $this->auditLogger->adminLogin(false, $command->ip, 'Invalid credentials');
            throw new \RuntimeException('Invalid credentials');
        }

        $this->auditLogger->adminLogin(true, $command->ip);

        return $session;
    }
}
