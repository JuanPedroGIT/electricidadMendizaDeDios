<?php

declare(strict_types=1);

namespace App\Domain\Service\Repository;

use App\Domain\Service\Entity\Service;
use App\Domain\Shared\ValueObject\Uuid;

interface ServiceRepositoryInterface
{
    public function save(Service $service): void;

    public function delete(Service $service): void;

    public function findById(Uuid $id): ?Service;

    public function findBySlug(string $slug): ?Service;

    /**
     * @return Service[]
     */
    public function findAll(): array;

    public function existsBySlug(string $slug, ?Uuid $excludeId = null): bool;

    public function count(): int;
}
