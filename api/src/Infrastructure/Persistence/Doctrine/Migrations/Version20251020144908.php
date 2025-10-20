<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251020144908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add networks table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE networks (name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\', id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX network_name_unique_constraint ON networks (name)');
        $this->addSql('ALTER TABLE tvshows ADD network_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE tvshows ADD CONSTRAINT FK_F229C3C334128B91 FOREIGN KEY (network_id) REFERENCES networks (id)');
        $this->addSql('CREATE INDEX shows_network_id ON tvshows (network_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE networks');
        $this->addSql('ALTER TABLE tvshows DROP CONSTRAINT FK_F229C3C334128B91');
        $this->addSql('DROP INDEX shows_network_id');
        $this->addSql('ALTER TABLE tvshows DROP network_id');
    }
}
