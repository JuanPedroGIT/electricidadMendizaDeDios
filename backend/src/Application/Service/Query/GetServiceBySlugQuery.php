<?php

declare(strict_types=1);

namespace App\Application\Service\Query;

use App\Application\Shared\Query\QueryInterface;

final readonly class GetServiceBySlugQuery implements QueryInterface
{
    public function __construct(
        public string $slug
    ) {
    }
}
