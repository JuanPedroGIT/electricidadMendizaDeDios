<?php

declare(strict_types=1);

namespace App\Application\SiteSetting\Query;

use App\Application\Shared\Query\QueryHandlerInterface;
use App\Application\Shared\Query\QueryInterface;
use App\Domain\SiteSetting\Repository\SiteSettingRepositoryInterface;
use App\Infrastructure\Data\SiteContent;

final class GetPublicSiteSettingsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private SiteSettingRepositoryInterface $siteSettingRepository
    ) {
    }

    public function handle(QueryInterface $query): array
    {
        if (!$query instanceof GetPublicSiteSettingsQuery) {
            throw new \InvalidArgumentException('Invalid query type');
        }

        $setting = $this->siteSettingRepository->findByKeyName('public');

        return $setting?->getData() ?? SiteContent::siteSettingsPublic();
    }
}
