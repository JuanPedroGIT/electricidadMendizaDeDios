<?php

declare(strict_types=1);

namespace App\Application\Faq\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Command\CommandInterface;
use App\Domain\Faq\Entity\Faq;
use App\Domain\Faq\Repository\FaqRepositoryInterface;

final class CreateFaqCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private FaqRepositoryInterface $faqRepository
    ) {
    }

    public function handle(CommandInterface $command): string
    {
        if (!$command instanceof CreateFaqCommand) {
            throw new \InvalidArgumentException('Invalid command type');
        }

        $faq = Faq::create(
            $command->question,
            $command->answer
        );

        $this->faqRepository->save($faq);

        return $faq->getId()->toString();
    }
}
