<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\SiteSetting\Entity\SiteSetting;
use App\Domain\SiteSetting\Repository\SiteSettingRepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Persistence\Doctrine\Entity\SiteSettingOrmEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineSiteSettingRepository implements SiteSettingRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(SiteSetting $siteSetting): void
    {
        $ormEntity = $this->entityManager
            ->getRepository(SiteSettingOrmEntity::class)
            ->find($siteSetting->getId()->toString());

        if ($ormEntity === null) {
            $ormEntity = new SiteSettingOrmEntity();
            $ormEntity->setId(Uuid::fromString($siteSetting->getId()->toString()));
            $ormEntity->setCreatedAt($siteSetting->getCreatedAt()->toDateTimeImmutable());
            $this->entityManager->persist($ormEntity);
        }

        $ormEntity->setKeyName($siteSetting->getKeyName());
        $ormEntity->setData($siteSetting->getData());
        $ormEntity->setUpdatedAt($siteSetting->getUpdatedAt()->toDateTimeImmutable());

        $this->entityManager->flush();
    }

    public function findById(Uuid $id): ?SiteSetting
    {
        $ormEntity = $this->entityManager
            ->getRepository(SiteSettingOrmEntity::class)
            ->find($id->toString());

        return $ormEntity ? $this->toDomainEntity($ormEntity) : null;
    }

    public function findByKeyName(string $keyName): ?SiteSetting
    {
        $ormEntity = $this->entityManager
            ->getRepository(SiteSettingOrmEntity::class)
            ->findOneBy(['keyName' => $keyName]);

        return $ormEntity ? $this->toDomainEntity($ormEntity) : null;
    }

    public function count(): int
    {
        return (int) $this->entityManager
            ->getRepository(SiteSettingOrmEntity::class)
            ->count([]);
    }

    private function toDomainEntity(SiteSettingOrmEntity $orm): SiteSetting
    {
        return new SiteSetting(
            Uuid::fromString($orm->getId()->toRfc4122()),
            $orm->getKeyName(),
            $orm->getData() ?? [],
            \App\Domain\Shared\ValueObject\DateTime::fromDateTimeImmutable($orm->getCreatedAt()),
            \App\Domain\Shared\ValueObject\DateTime::fromDateTimeImmutable($orm->getUpdatedAt())
        );
    }
}
