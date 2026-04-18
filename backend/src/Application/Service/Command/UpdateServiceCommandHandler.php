<?php

declare(strict_types=1);

namespace App\Application\Service\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Command\CommandInterface;
use App\Domain\Service\Repository\ServiceRepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;

final class UpdateServiceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ServiceRepositoryInterface $serviceRepository
    ) {
    }

    public function handle(CommandInterface $command): mixed
    {
        if (!$command instanceof UpdateServiceCommand) {
            throw new \InvalidArgumentException('Invalid command type');
        }

        $service = $this->serviceRepository->findById(Uuid::fromString($command->id));

        if ($service === null) {
            throw new \RuntimeException('Service not found');
        }

        if ($this->serviceRepository->existsBySlug($command->slug, Uuid::fromString($command->id))) {
            throw new \RuntimeException('Slug already exists');
        }

        $service->update(
            $command->slug,
            $command->name,
            $command->summary,
            $command->benefits
        );

        $this->serviceRepository->save($service);
    }
}
