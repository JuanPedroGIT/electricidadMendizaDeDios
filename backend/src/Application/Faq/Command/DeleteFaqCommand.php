<?php

declare(strict_types=1);

namespace App\Application\Faq\Command;

use App\Application\Shared\Command\CommandInterface;

final readonly class DeleteFaqCommand implements CommandInterface
{
    public function __construct(
        public string $id
    ) {
    }
}
