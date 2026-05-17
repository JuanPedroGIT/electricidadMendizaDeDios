<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Contact\Entity\ContactLead;
use App\Domain\Contact\Repository\ContactLeadRepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Persistence\Doctrine\Entity\ContactLeadOrmEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid as OrmUuid;

final class DoctrineContactLeadRepository implements ContactLeadRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(ContactLead $contactLead): void
    {
        $ormEntity = $this->entityManager
            ->getRepository(ContactLeadOrmEntity::class)
            ->find($contactLead->getId()->toString());

        if ($ormEntity === null) {
            $ormEntity = new ContactLeadOrmEntity();
            $ormEntity->setId(OrmUuid::fromString($contactLead->getId()->toString()));
            $ormEntity->setCreatedAt($contactLead->getCreatedAt()->toDateTimeImmutable());
            $this->entityManager->persist($ormEntity);
        }

        $ormEntity->setName($contactLead->getName());
        $ormEntity->setPhone($contactLead->getPhone()->toString());
        $ormEntity->setEmail($contactLead->getEmail()?->toString());
        $ormEntity->setType($contactLead->getType());
        $ormEntity->setArea($contactLead->getArea());
        $ormEntity->setMessage($contactLead->getMessage());
        $ormEntity->setIp($contactLead->getIp());
        $ormEntity->setUserAgent($contactLead->getUserAgent());
        if ($contactLead->getSendDate() !== null) {
            $ormEntity->setSendDate($contactLead->getSendDate()->toDateTimeImmutable());
        }

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

    public function findPending(int $limit): array
    {
        $ormEntities = $this->entityManager
            ->getRepository(ContactLeadOrmEntity::class)
            ->createQueryBuilder('c')
            ->where('c.sendDate IS NULL')
            ->orderBy('c.createdAt', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return array_map(
            fn ($entity) => $this->toDomainEntity($entity),
            $ormEntities
        );
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
            \App\Domain\Shared\ValueObject\DateTime::fromDateTimeImmutable($orm->getCreatedAt()),
            $orm->getSendDate() ? \App\Domain\Shared\ValueObject\DateTime::fromDateTimeImmutable($orm->getSendDate()) : null
        );
    }
}
