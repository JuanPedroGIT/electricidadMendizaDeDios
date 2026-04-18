<?php

declare(strict_types=1);

namespace App\Domain\SiteSetting\Entity;

use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Shared\ValueObject\DateTime;

final class SiteSetting
{
    private Uuid $id;
    private string $keyName;
    private array $data;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        Uuid $id,
        string $keyName,
        array $data,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->keyName = $keyName;
        $this->data = $data;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(string $keyName, array $data): self
    {
        $now = DateTime::now();
        return new self(
            Uuid::generate(),
            $keyName,
            $data,
            $now,
            $now
        );
    }

    public function update(array $data): void
    {
        $this->data = $data;
        $this->updatedAt = DateTime::now();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getKeyName(): string
    {
        return $this->keyName;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
}
