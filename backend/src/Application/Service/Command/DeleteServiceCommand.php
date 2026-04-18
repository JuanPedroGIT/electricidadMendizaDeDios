<?php

declare(strict_types=1);

namespace App\Application\Service\Command;

use App\Application\Shared\Command\CommandInterface;

final readonly class DeleteServiceCommand implements CommandInterface
{
    public function __construct(
        public string $id
    ) {
    }
}
