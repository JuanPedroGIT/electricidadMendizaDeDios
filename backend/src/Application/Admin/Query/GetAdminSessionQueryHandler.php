<?php

declare(strict_types=1);

namespace App\Application\Admin\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Application\Shared\Query\QueryInterface;
use App\Domain\Admin\Entity\AdminSession;

final class GetAdminSessionQueryHandler implements QueryHandlerInterface
{
    public function handle(QueryInterface $query): AdminSession
    {
        if (!$query instanceof GetAdminSessionQuery) {
            throw new \InvalidArgumentException('Invalid query type');
        }

        return new AdminSession(
            $query->isAuthenticated,
            $query->loggedAt,
            $query->csrfToken
        );
    }
}
