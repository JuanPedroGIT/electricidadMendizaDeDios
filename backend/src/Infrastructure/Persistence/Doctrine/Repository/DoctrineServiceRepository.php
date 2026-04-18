<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Service\Entity\Service;
use App\Domain\Service\Repository\ServiceRepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Persistence\Doctrine\Entity\ServiceOrmEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineServiceRepository implements ServiceRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(Service $service): void
    {
        $ormEntity = $this->entityManager
            ->getRepository(ServiceOrmEntity::class)
            ->find($service->getId()->toString());

        if ($ormEntity === null) {
            $ormEntity = new ServiceOrmEntity();
            $ormEntity->setId(Uuid::fromString($service->getId()->toString()));
            $ormEntity->setCreatedAt($service->getCreatedAt()->toDateTimeImmutable());
            $this->entityManager->persist($ormEntity);
        }

        $ormEntity->setSlug($service->getSlug());
        $ormEntity->setName($service->getName());
        $ormEntity->setSummary($service->getSummary());
        $ormEntity->setBenefits($service->getBenefits());
        $ormEntity->setUpdatedAt($service->getUpdatedAt()->toDateTimeImmutable());

        $this->entityManager->flush();
    }

    public function delete(Service $service): void
    {
        $ormEntity = $this->entityManager
            ->getRepository(ServiceOrmEntity::class)
            ->find($service->getId()->toString());

        if ($ormEntity !== null) {
            $this->entityManager->remove($ormEntity);
            $this->entityManager->flush();
        }
    }

    public function findById(Uuid $id): ?Service
    {
        $ormEntity = $this->entityManager
            ->getRepository(ServiceOrmEntity::class)
            ->find($id->toString());

        return $ormEntity ? $this->toDomainEntity($ormEntity) : null;
    }

    public function findBySlug(string $slug): ?Service
    {
        $ormEntity = $this->entityManager
            ->getRepository(ServiceOrmEntity::class)
            ->findOneBy(['slug' => $slug]);

        return $ormEntity ? $this->toDomainEntity($ormEntity) : null;
    }

    public function findAll(): array
    {
        $ormEntities = $this->entityManager
            ->getRepository(ServiceOrmEntity::class)
            ->findAll();

        return array_map(
            fn ($entity) => $this->toDomainEntity($entity),
            $ormEntities
        );
    }

    public function existsBySlug(string $slug, ?Uuid $excludeId = null): bool
    {
        $qb = $this->entityManager
            ->getRepository(ServiceOrmEntity::class)
            ->createQueryBuilder('s')
            ->where('s.slug = :slug')
            ->setParameter('slug', $slug);

        if ($excludeId !== null) {
            $qb->andWhere('s.id != :id')
                ->setParameter('id', $excludeId->toString());
        }

        return (bool) $qb->select('count(s.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function count(): int
    {
        return (int) $this->entityManager
            ->getRepository(ServiceOrmEntity::class)
            ->count([]);
    }

    private function toDomainEntity(ServiceOrmEntity $orm): Service
    {
        return new Service(
            Uuid::fromString($orm->getId()->toRfc4122()),
            $orm->getSlug(),
            $orm->getName(),
            $orm->getSummary(),
            $orm->getBenefits(),
            \App\Domain\Shared\ValueObject\DateTime::fromDateTimeImmutable($orm->getCreatedAt()),
            \App\Domain\Shared\ValueObject\DateTime::fromDateTimeImmutable($orm->getUpdatedAt())
        );
    }
}
