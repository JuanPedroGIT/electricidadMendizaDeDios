<?php

declare(strict_types=1);

namespace App\Application\Admin\Query;

use App\Application\Shared\Query\QueryInterface;

final readonly class GetAdminSessionQuery implements QueryInterface
{
    public function __construct(
        public bool $isAuthenticated,
        public ?int $loggedAt,
        public ?string $csrfToken
    ) {
    }
}
