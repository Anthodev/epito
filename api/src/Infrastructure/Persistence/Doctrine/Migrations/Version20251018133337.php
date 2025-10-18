<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251018133337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'added seasons tables and updated relationships';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE seasons (number INT NOT NULL, episode_count INT NOT NULL, poster VARCHAR(255) DEFAULT NULL, premiere_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\', id UUID NOT NULL, tv_show_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX IDX_B4F4301C5E3A35BB ON seasons (tv_show_id)');
        $this->addSql('ALTER TABLE seasons ADD CONSTRAINT FK_B4F4301C5E3A35BB FOREIGN KEY (tv_show_id) REFERENCES tvshows (id)');
        $this->addSql('ALTER TABLE episodes ADD season_id UUID NOT NULL');
        $this->addSql('ALTER TABLE episodes ADD CONSTRAINT FK_7DD55EDD4EC001D1 FOREIGN KEY (season_id) REFERENCES seasons (id)');
        $this->addSql('CREATE INDEX IDX_7DD55EDD4EC001D1 ON episodes (season_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seasons DROP CONSTRAINT FK_B4F4301C5E3A35BB');
        $this->addSql('DROP TABLE seasons');
        $this->addSql('ALTER TABLE episodes DROP CONSTRAINT FK_7DD55EDD4EC001D1');
        $this->addSql('DROP INDEX IDX_7DD55EDD4EC001D1');
        $this->addSql('ALTER TABLE episodes DROP season_id');
    }
}
