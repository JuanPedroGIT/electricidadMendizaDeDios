<?php

declare(strict_types=1);

namespace App\Domain\SiteSetting\Repository;

use App\Domain\SiteSetting\Entity\SiteSetting;
use App\Domain\Shared\ValueObject\Uuid;

interface SiteSettingRepositoryInterface
{
    public function save(SiteSetting $siteSetting): void;

    public function findById(Uuid $id): ?SiteSetting;

    public function findByKeyName(string $keyName): ?SiteSetting;

    public function count(): int;
}
