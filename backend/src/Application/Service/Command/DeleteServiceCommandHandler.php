<?php

declare(strict_types=1);

namespace App\Application\Service\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Command\CommandInterface;
use App\Domain\Service\Repository\ServiceRepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;

final class DeleteServiceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ServiceRepositoryInterface $serviceRepository
    ) {
    }

    public function handle(CommandInterface $command): mixed
    {
        if (!$command instanceof DeleteServiceCommand) {
            throw new \InvalidArgumentException('Invalid command type');
        }

        $service = $this->serviceRepository->findById(Uuid::fromString($command->id));

        if ($service === null) {
            throw new \DomainException('Service not found');
        }

        $this->serviceRepository->delete($service);
    }
}
