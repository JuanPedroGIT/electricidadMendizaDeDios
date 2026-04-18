<?php

declare(strict_types=1);

namespace App\Domain\Faq\Repository;

use App\Domain\Faq\Entity\Faq;
use App\Domain\Shared\ValueObject\Uuid;

interface FaqRepositoryInterface
{
    public function save(Faq $faq): void;

    public function delete(Faq $faq): void;

    public function findById(Uuid $id): ?Faq;

    /**
     * @return Faq[]
     */
    public function findAll(): array;

    public function count(): int;
}
