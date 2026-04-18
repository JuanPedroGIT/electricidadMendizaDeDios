<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

final readonly class DateTime implements \Stringable
{
    private function __construct(
        private \DateTimeImmutable $value
    ) {
    }

    public static function now(): self
    {
        return new self(new \DateTimeImmutable());
    }

    public static function fromString(string $datetime): self
    {
        return new self(new \DateTimeImmutable($datetime));
    }

    public static function fromDateTimeImmutable(\DateTimeImmutable $datetime): self
    {
        return new self($datetime);
    }

    public function toDateTimeImmutable(): \DateTimeImmutable
    {
        return $this->value;
    }

    public function toString(): string
    {
        return $this->value->format(\DateTimeInterface::ATOM);
    }

    public function format(string $format): string
    {
        return $this->value->format($format);
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function equals(self $other): bool
    {
        return $this->value->getTimestamp() === $other->value->getTimestamp();
    }
}
