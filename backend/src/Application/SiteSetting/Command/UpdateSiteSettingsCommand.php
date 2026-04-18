<?php

declare(strict_types=1);

namespace App\Application\SiteSetting\Command;

use App\Application\Shared\Command\CommandInterface;

final readonly class UpdateSiteSettingsCommand implements CommandInterface
{
    public function __construct(
        public array $data
    ) {
    }
}
