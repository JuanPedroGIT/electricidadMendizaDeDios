<?php

declare(strict_types=1);

namespace App\Domain\Service\Entity;

use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Shared\ValueObject\DateTime;

final class Service
{
    private Uuid $id;
    private string $slug;
    private string $name;
    private string $summary;
    private array $benefits;
    private DateTime $createdAt;
    private DateTime $updatedAt;

    public function __construct(
        Uuid $id,
        string $slug,
        string $name,
        string $summary,
        array $benefits,
        DateTime $createdAt,
        DateTime $updatedAt
    ) {
        $this->id = $id;
        $this->slug = $slug;
        $this->name = $name;
        $this->summary = $summary;
        $this->benefits = $benefits;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        string $slug,
        string $name,
        string $summary,
        array $benefits
    ): self {
        $now = DateTime::now();
        return new self(
            Uuid::generate(),
            $slug,
            $name,
            $summary,
            $benefits,
            $now,
            $now
        );
    }

    public function update(
        string $slug,
        string $name,
        string $summary,
        array $benefits
    ): void {
        $this->slug = $slug;
        $this->name = $name;
        $this->summary = $summary;
        $this->benefits = $benefits;
        $this->updatedAt = DateTime::now();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSummary(): string
    {
        return $this->summary;
    }

    public function getBenefits(): array
    {
        return $this->benefits;
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
