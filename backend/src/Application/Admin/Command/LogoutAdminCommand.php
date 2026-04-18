<?php

declare(strict_types=1);

namespace App\Application\Admin\Command;

use App\Application\Shared\Command\CommandInterface;

final readonly class LogoutAdminCommand implements CommandInterface
{
    public function __construct()
    {
    }
}
