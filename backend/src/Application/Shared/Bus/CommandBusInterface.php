<?php

declare(strict_types=1);

namespace App\Application\Shared\Bus;

use App\Application\Shared\Command\CommandInterface;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): void;
}
