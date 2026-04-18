<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Faq\Entity\Faq;
use App\Domain\Faq\Repository\FaqRepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;
use App\Infrastructure\Persistence\Doctrine\Entity\FaqOrmEntity;
use Doctrine\ORM\EntityManagerInterface;

final class DoctrineFaqRepository implements FaqRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(Faq $faq): void
    {
        $ormEntity = $this->entityManager
            ->getRepository(FaqOrmEntity::class)
            ->find($faq->getId()->toString());

        if ($ormEntity === null) {
            $ormEntity = new FaqOrmEntity();
            $ormEntity->setId(Uuid::fromString($faq->getId()->toString()));
            $ormEntity->setCreatedAt($faq->getCreatedAt()->toDateTimeImmutable());
            $this->entityManager->persist($ormEntity);
        }

        $ormEntity->setQuestion($faq->getQuestion());
        $ormEntity->setAnswer($faq->getAnswer());

        $this->entityManager->flush();
    }

    public function delete(Faq $faq): void
    {
        $ormEntity = $this->entityManager
            ->getRepository(FaqOrmEntity::class)
            ->find($faq->getId()->toString());

        if ($ormEntity !== null) {
            $this->entityManager->remove($ormEntity);
            $this->entityManager->flush();
        }
    }

    public function findById(Uuid $id): ?Faq
    {
        $ormEntity = $this->entityManager
            ->getRepository(FaqOrmEntity::class)
            ->find($id->toString());

        return $ormEntity ? $this->toDomainEntity($ormEntity) : null;
    }

    public function findAll(): array
    {
        $ormEntities = $this->entityManager
            ->getRepository(FaqOrmEntity::class)
            ->findAll();

        return array_map(
            fn ($entity) => $this->toDomainEntity($entity),
            $ormEntities
        );
    }

    public function count(): int
    {
        return (int) $this->entityManager
            ->getRepository(FaqOrmEntity::class)
            ->count([]);
    }

    private function toDomainEntity(FaqOrmEntity $orm): Faq
    {
        return new Faq(
            Uuid::fromString($orm->getId()->toRfc4122()),
            $orm->getQuestion(),
            $orm->getAnswer(),
            \App\Domain\Shared\ValueObject\DateTime::fromDateTimeImmutable($orm->getCreatedAt())
        );
    }
}
