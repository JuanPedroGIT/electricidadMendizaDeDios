<?php

declare(strict_types=1);

namespace App\Application\Shared\Bus;

use App\Application\Shared\Query\QueryInterface;

interface QueryBusInterface
{
    /**
     * @return mixed
     */
    public function ask(QueryInterface $query);
}
