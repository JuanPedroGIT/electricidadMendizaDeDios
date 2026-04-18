<?php

declare(strict_types=1);

namespace App\Application\Faq\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Command\CommandInterface;
use App\Domain\Faq\Repository\FaqRepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;

final class UpdateFaqCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private FaqRepositoryInterface $faqRepository
    ) {
    }

    public function handle(CommandInterface $command): mixed
    {
        if (!$command instanceof UpdateFaqCommand) {
            throw new \InvalidArgumentException('Invalid command type');
        }

        $faq = $this->faqRepository->findById(Uuid::fromString($command->id));

        if ($faq === null) {
            throw new \RuntimeException('FAQ not found');
        }

        $faq->update(
            $command->question,
            $command->answer
        );

        $this->faqRepository->save($faq);
    }
}
