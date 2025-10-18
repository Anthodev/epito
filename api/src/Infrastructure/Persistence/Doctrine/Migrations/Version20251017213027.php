<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251017213027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add tvshows table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tvshows (name VARCHAR(255) NOT NULL, summary TEXT DEFAULT NULL, status VARCHAR(50) DEFAULT \'in_development\' NOT NULL, poster VARCHAR(255) DEFAULT NULL, website VARCHAR(255) DEFAULT NULL, rating DOUBLE PRECISION DEFAULT NULL, language VARCHAR(50) DEFAULT NULL, slug VARCHAR(255) NOT NULL, runtime INT DEFAULT NULL, premiered VARCHAR(255) DEFAULT NULL, id_tvmaze INT NOT NULL, id_imdb INT DEFAULT NULL, id_tvdb INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\', id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX shows_name ON tvshows (name)');
        $this->addSql('CREATE UNIQUE INDEX shows_slug_unique_constraint ON tvshows (slug)');
        $this->addSql('CREATE UNIQUE INDEX shows_id_tvmaze_unique_constraint ON tvshows (id_tvmaze)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tvshows');
    }
}
