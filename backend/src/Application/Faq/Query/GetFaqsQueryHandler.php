<?php

declare(strict_types=1);

namespace App\Application\Faq\Query;

use App\Application\Faq\DTO\FaqDTO;
use App\Application\Shared\Query\QueryHandlerInterface;
use App\Application\Shared\Query\QueryInterface;
use App\Domain\Faq\Repository\FaqRepositoryInterface;

final class GetFaqsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private FaqRepositoryInterface $faqRepository
    ) {
    }

    public function handle(QueryInterface $query): array
    {
        if (!$query instanceof GetFaqsQuery) {
            throw new \InvalidArgumentException('Invalid query type');
        }

        $faqs = $this->faqRepository->findAll();

        return array_map(fn ($faq) => new FaqDTO(
            $faq->getId()->toString(),
            $faq->getQuestion(),
            $faq->getAnswer()
        ), $faqs);
    }
}
