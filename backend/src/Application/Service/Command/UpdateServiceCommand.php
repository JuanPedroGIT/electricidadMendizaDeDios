<?php

declare(strict_types=1);

namespace App\Application\Service\Command;

use App\Application\Shared\Command\CommandInterface;

final readonly class UpdateServiceCommand implements CommandInterface
{
    /**
     * @param string[] $benefits
     */
    public function __construct(
        public string $id,
        public string $slug,
        public string $name,
        public string $summary,
        public array $benefits
    ) {
    }
}
