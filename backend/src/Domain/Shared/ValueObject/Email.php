<?php

declare(strict_types=1);

namespace App\Domain\Shared\ValueObject;

final readonly class Email implements \Stringable
{
    private function __construct(
        private string $value
    ) {
    }

    public static function fromString(string $email): self
    {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address');
        }

        return new self(strtolower($email));
    }

    public static function fromNullable(?string $email): ?self
    {
        if ($email === null || $email === '') {
            return null;
        }

        return self::fromString($email);
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
