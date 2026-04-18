<?php

declare(strict_types=1);

namespace App\Domain\Contact\Repository;

use App\Domain\Contact\Entity\ContactLead;
use App\Domain\Shared\ValueObject\Uuid;

interface ContactLeadRepositoryInterface
{
    public function save(ContactLead $contactLead): void;

    public function findById(Uuid $id): ?ContactLead;

    /**
     * @return ContactLead[]
     */
    public function findAll(): array;
}
