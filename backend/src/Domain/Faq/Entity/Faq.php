<?php

declare(strict_types=1);

namespace App\Domain\Faq\Entity;

use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Shared\ValueObject\DateTime;

final class Faq
{
    private Uuid $id;
    private string $question;
    private string $answer;
    private DateTime $createdAt;

    public function __construct(
        Uuid $id,
        string $question,
        string $answer,
        DateTime $createdAt
    ) {
        $this->id = $id;
        $this->question = $question;
        $this->answer = $answer;
        $this->createdAt = $createdAt;
    }

    public static function create(
        string $question,
        string $answer
    ): self {
        return new self(
            Uuid::generate(),
            $question,
            $answer,
            DateTime::now()
        );
    }

    public function update(string $question, string $answer): void
    {
        $this->question = $question;
        $this->answer = $answer;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
}
