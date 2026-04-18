<?php

declare(strict_types=1);

namespace App\Application\Faq\Command;

use App\Application\Shared\Command\CommandInterface;

final readonly class UpdateFaqCommand implements CommandInterface
{
    public function __construct(
        public string $id,
        public string $question,
        public string $answer
    ) {
    }
}
