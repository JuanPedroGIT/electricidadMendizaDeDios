<?php

declare(strict_types=1);

namespace App\Application\Service\DTO;

final readonly class ServiceDTO
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
