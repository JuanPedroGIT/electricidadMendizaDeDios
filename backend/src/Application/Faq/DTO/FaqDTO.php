<?php

declare(strict_types=1);

namespace App\Application\Faq\DTO;

final readonly class FaqDTO
{
    public function __construct(
        public string $id,
        public string $question,
        public string $answer
    ) {
    }
}
