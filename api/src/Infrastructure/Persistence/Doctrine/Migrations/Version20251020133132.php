<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251020133132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'added tvshow_types table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tvshow_types (name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\', id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX show_types_name_unique_constraint ON tvshow_types (name)');
        $this->addSql('ALTER TABLE tvshows ADD show_type_id UUID NOT NULL');
        $this->addSql('ALTER TABLE tvshows ADD CONSTRAINT FK_F229C3C3A21EA170 FOREIGN KEY (show_type_id) REFERENCES tvshow_types (id)');
        $this->addSql('CREATE INDEX shows_show_type_id ON tvshows (show_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tvshow_types');
        $this->addSql('ALTER TABLE tvshows DROP CONSTRAINT FK_F229C3C3A21EA170');
        $this->addSql('DROP INDEX shows_show_type_id');
        $this->addSql('ALTER TABLE tvshows DROP show_type_id');
    }
}
