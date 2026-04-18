<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260404103000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Base tables: services, faqs, site_settings, contact_leads';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE services (id UUID NOT NULL, slug VARCHAR(180) NOT NULL, name VARCHAR(180) NOT NULL, summary TEXT NOT NULL, benefits JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_services_slug ON services (slug)');

        $this->addSql('CREATE TABLE faqs (id UUID NOT NULL, question VARCHAR(255) NOT NULL, answer TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');

        $this->addSql('CREATE TABLE site_settings (id UUID NOT NULL, key_name VARCHAR(50) NOT NULL, data JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_site_settings_key ON site_settings (key_name)');

        $this->addSql('CREATE TABLE contact_leads (id UUID NOT NULL, name VARCHAR(180) NOT NULL, phone VARCHAR(40) NOT NULL, email VARCHAR(180) DEFAULT NULL, type VARCHAR(100) DEFAULT NULL, area VARCHAR(120) DEFAULT NULL, message TEXT DEFAULT NULL, ip VARCHAR(120) DEFAULT NULL, user_agent VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE contact_leads');
        $this->addSql('DROP TABLE faqs');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE site_settings');
    }
}
