<?php

declare(strict_types=1);

namespace App\Application\Admin\DTO;

final readonly class AdminSessionDTO
{
    public function __construct(
        public bool $isAuthenticated,
        public ?string $csrfToken
    ) {
    }
}
