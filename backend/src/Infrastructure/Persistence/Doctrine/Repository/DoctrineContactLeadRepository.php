<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Contact\Entity\ContactLead;
use App\Domain\Contact\Repository\ContactLeadRepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Persistence\Doctrine\Entity\ContactLeadOrmEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineContactLeadRepository implements ContactLeadRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(ContactLead $contactLead): void
    {
        $ormEntity = $this->toOrmEntity($contactLead);
        $this->entityManager->persist($ormEntity);
        $this->entityManager->flush();
    }

    public function findById(Uuid $id): ?ContactLead
    {
        $ormEntity = $this->entityManager
            ->getRepository(ContactLeadOrmEntity::class)
            ->find($id->toString());

        return $ormEntity ? $this->toDomainEntity($ormEntity) : null;
    }

    public function findAll(): array
    {
        $ormEntities = $this->entityManager
            ->getRepository(ContactLeadOrmEntity::class)
            ->findAll();

        return array_map(
            fn ($entity) => $this->toDomainEntity($entity),
            $ormEntities
        );
    }

    private function toOrmEntity(ContactLead $domain): ContactLeadOrmEntity
    {
        $orm = new ContactLeadOrmEntity();
        $orm->setId(Uuid::fromString($domain->getId()->toString()));
        $orm->setName($domain->getName());
        $orm->setPhone($domain->getPhone()->toString());
        $orm->setEmail($domain->getEmail()?->toString());
        $orm->setType($domain->getType());
        $orm->setArea($domain->getArea());
        $orm->setMessage($domain->getMessage());
        $orm->setIp($domain->getIp());
        $orm->setUserAgent($domain->getUserAgent());
        $orm->setCreatedAt($domain->getCreatedAt()->toDateTimeImmutable());

        return $orm;
    }

    private function toDomainEntity(ContactLeadOrmEntity $orm): ContactLead
    {
        return new ContactLead(
            Uuid::fromString($orm->getId()->toRfc4122()),
            $orm->getName(),
            \App\Domain\Shared\ValueObject\Phone::fromString($orm->getPhone()),
            \App\Domain\Shared\ValueObject\Email::fromNullable($orm->getEmail()),
            $orm->getType(),
            $orm->getArea(),
            $orm->getMessage(),
            $orm->getIp(),
            $orm->getUserAgent(),
            \App\Domain\Shared\ValueObject\DateTime::fromDateTimeImmutable($orm->getCreatedAt())
        );
    }
}
