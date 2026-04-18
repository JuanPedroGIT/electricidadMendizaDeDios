<?php

declare(strict_types=1);

namespace App\Domain\Contact\Entity;

use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Shared\ValueObject\DateTime;
use App\Domain\Shared\ValueObject\Email;
use App\Domain\Shared\ValueObject\Phone;

final class ContactLead
{
    private Uuid $id;
    private string $name;
    private Phone $phone;
    private ?Email $email;
    private ?string $type;
    private ?string $area;
    private ?string $message;
    private ?string $ip;
    private ?string $userAgent;
    private DateTime $createdAt;

    public function __construct(
        Uuid $id,
        string $name,
        Phone $phone,
        ?Email $email,
        ?string $type,
        ?string $area,
        ?string $message,
        ?string $ip,
        ?string $userAgent,
        DateTime $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->phone = $phone;
        $this->email = $email;
        $this->type = $type;
        $this->area = $area;
        $this->message = $message;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
        $this->createdAt = $createdAt;
    }

    public static function create(
        string $name,
        Phone $phone,
        ?Email $email,
        ?string $type,
        ?string $area,
        ?string $message,
        ?string $ip,
        ?string $userAgent
    ): self {
        return new self(
            Uuid::generate(),
            $name,
            $phone,
            $email,
            $type,
            $area,
            $message,
            $ip,
            $userAgent,
            DateTime::now()
        );
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
