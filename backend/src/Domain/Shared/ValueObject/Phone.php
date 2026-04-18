<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

final readonly class Phone implements \Stringable
{
    private function __construct(
        private string $value
    ) {
    }

    public static function fromString(string $phone): self
    {
        $phone = preg_replace('/\s+/', ' ', trim($phone));

        if (!preg_match('/^[0-9+\s-]{6,20}$/', $phone)) {
            throw new \InvalidArgumentException('Invalid phone number format');
        }

        return new self($phone);
    }

    public function toString(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
