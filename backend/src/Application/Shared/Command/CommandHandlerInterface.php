<?php

declare(strict_types=1);

namespace App\Application\Shared\Command;

interface CommandHandlerInterface
{
    /**
     * @return mixed Returns the result of command execution
     */
    public function handle(CommandInterface $command): mixed;
}
