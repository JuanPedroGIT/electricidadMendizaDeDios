<?php

declare(strict_types=1);

namespace App\Application\Contact\Event;

final readonly class ContactLeadCreatedEvent
{
    public function __construct(
        public string $id,
        public string $name,
        public string $phone,
        public ?string $email,
        public ?string $type,
        public ?string $area,
        public ?string $message
    ) {
    }
}
