<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251020134245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'added genres table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE genres (name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\', id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE UNIQUE INDEX genre_name_unique_constraint ON genres (name)');
        $this->addSql('CREATE TABLE shows_genres (show_id UUID NOT NULL, genre_id UUID NOT NULL, PRIMARY KEY (show_id, genre_id))');
        $this->addSql('CREATE INDEX IDX_9853586FD0C1FC64 ON shows_genres (show_id)');
        $this->addSql('CREATE INDEX IDX_9853586F4296D31F ON shows_genres (genre_id)');
        $this->addSql('ALTER TABLE shows_genres ADD CONSTRAINT FK_9853586FD0C1FC64 FOREIGN KEY (show_id) REFERENCES tvshows (id)');
        $this->addSql('ALTER TABLE shows_genres ADD CONSTRAINT FK_9853586F4296D31F FOREIGN KEY (genre_id) REFERENCES genres (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shows_genres DROP CONSTRAINT FK_9853586FD0C1FC64');
        $this->addSql('ALTER TABLE shows_genres DROP CONSTRAINT FK_9853586F4296D31F');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE shows_genres');
    }
}
