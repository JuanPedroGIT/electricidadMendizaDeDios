<?php

declare(strict_types=1);

namespace App\Application\SiteSetting\Command;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Command\CommandInterface;
use App\Domain\SiteSetting\Entity\SiteSetting;
use App\Domain\SiteSetting\Repository\SiteSettingRepositoryInterface;

final class UpdateSiteSettingsCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private SiteSettingRepositoryInterface $siteSettingRepository
    ) {
    }

    public function handle(CommandInterface $command): mixed
    {
        if (!$command instanceof UpdateSiteSettingsCommand) {
            throw new \InvalidArgumentException('Invalid command type');
        }

        $setting = $this->siteSettingRepository->findByKeyName('public');

        if ($setting === null) {
            $setting = SiteSetting::create('public', $command->data);
        } else {
            $setting->update($command->data);
        }

        $this->siteSettingRepository->save($setting);
    }
}
