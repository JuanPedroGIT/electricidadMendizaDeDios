<?php

declare(strict_types=1);

namespace App\Application\Shared\Query;

interface QueryHandlerInterface
{
    /**
     * @return mixed
     */
    public function handle(QueryInterface $query);
}
