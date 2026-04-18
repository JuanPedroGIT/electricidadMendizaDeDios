<?php

declare(strict_types=1);

namespace App\Application\Contact\Command;

use App\Application\Shared\Command\CommandInterface;

final readonly class CreateContactLeadCommand implements CommandInterface
{
    public function __construct(
        public string $name,
        public string $phone,
        public ?string $email,
        public ?string $type,
        public ?string $area,
        public ?string $message,
        public ?string $ip,
        public ?string $userAgent
    ) {
    }
}
