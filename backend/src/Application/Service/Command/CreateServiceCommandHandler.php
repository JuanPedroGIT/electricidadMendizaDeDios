<?php

declare(strict_types=1);

namespace App\Application\Service\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Command\CommandInterface;
use App\Domain\Service\Entity\Service;
use App\Domain\Service\Repository\ServiceRepositoryInterface;

final class CreateServiceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ServiceRepositoryInterface $serviceRepository
    ) {
    }

    public function handle(CommandInterface $command): string
    {
        if (!$command instanceof CreateServiceCommand) {
            throw new \InvalidArgumentException('Invalid command type');
        }

        if ($this->serviceRepository->existsBySlug($command->slug)) {
            throw new \RuntimeException('Slug already exists');
        }

        $service = Service::create(
            $command->slug,
            $command->name,
            $command->summary,
            $command->benefits
        );

        $this->serviceRepository->save($service);

        return $service->getId()->toString();
    }
}
