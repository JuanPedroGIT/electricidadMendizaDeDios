<?php

declare(strict_types=1);

namespace App\Infrastructure\DataSeeder;

use App\Domain\Faq\Entity\Faq;
use App\Domain\Faq\Repository\FaqRepositoryInterface;
use App\Domain\Service\Entity\Service;
use App\Domain\Service\Repository\ServiceRepositoryInterface;
use App\Domain\SiteSetting\Entity\SiteSetting;
use App\Domain\SiteSetting\Repository\SiteSettingRepositoryInterface;
use App\Infrastructure\Data\SiteContent;

final readonly class DatabaseSeeder
{
    public function __construct(
        private ServiceRepositoryInterface $serviceRepository,
        private FaqRepositoryInterface $faqRepository,
        private SiteSettingRepositoryInterface $siteSettingRepository
    ) {
    }

    public function seedIfEmpty(): void
    {
        $seeded = false;

        if ($this->serviceRepository->count() === 0) {
            foreach (SiteContent::services() as $serviceData) {
                $service = Service::create(
                    $serviceData['slug'],
                    $serviceData['name'],
                    $serviceData['summary'],
                    $serviceData['benefits']
                );
                $this->serviceRepository->save($service);
            }
            $seeded = true;
        }

        if ($this->faqRepository->count() === 0) {
            foreach (SiteContent::faqs() as $faqData) {
                $faq = Faq::create(
                    $faqData['question'],
                    $faqData['answer']
                );
                $this->faqRepository->save($faq);
            }
            $seeded = true;
        }

        if ($this->siteSettingRepository->count() === 0) {
            $setting = SiteSetting::create('public', SiteContent::siteSettingsPublic());
            $this->siteSettingRepository->save($setting);
            $seeded = true;
        }
    }
}
