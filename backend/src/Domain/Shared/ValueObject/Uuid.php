<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

use Symfony\Component\Uid\Uuid as SymfonyUuid;

final readonly class Uuid implements \Stringable
{
    private function __construct(
        private SymfonyUuid $value
    ) {
    }

    public static function generate(): self
    {
        return new self(SymfonyUuid::v7());
    }

    public static function fromString(string $uuid): self
    {
        return new self(SymfonyUuid::fromString($uuid));
    }

    public function toString(): string
    {
        return $this->value->toRfc4122();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function equals(self $other): bool
    {
        return $this->value->equals($other->value);
    }
}
