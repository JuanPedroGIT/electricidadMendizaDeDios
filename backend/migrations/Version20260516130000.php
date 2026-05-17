<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260516130000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add send_date column to contact_leads for n8n email queue';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact_leads ADD send_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE contact_leads DROP send_date');
    }
}
