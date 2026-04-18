<?php

declare(strict_types=1);

namespace App\Application\Admin\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Command\CommandInterface;
use App\Domain\Admin\Entity\AdminSession;
use App\Domain\Admin\Service\AuthenticationService;

final class LogoutAdminCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private AuthenticationService $authenticationService
    ) {
    }

    public function handle(CommandInterface $command): AdminSession
    {
        if (!$command instanceof LogoutAdminCommand) {
            throw new \InvalidArgumentException('Invalid command type');
        }

        $session = AdminSession::createEmpty();
        $this->authenticationService->logout($session);

        return $session;
    }
}
