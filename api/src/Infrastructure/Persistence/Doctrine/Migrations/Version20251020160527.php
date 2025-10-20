<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251020160527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add followings table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE followings (start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'NOW()\', id UUID NOT NULL, user_id UUID NOT NULL, episode_id UUID NOT NULL, season_id UUID NOT NULL, show_id UUID NOT NULL, PRIMARY KEY (id))');
        $this->addSql('CREATE INDEX followings_user_id ON followings (user_id)');
        $this->addSql('CREATE INDEX followings_episode_id ON followings (episode_id)');
        $this->addSql('CREATE INDEX followings_season_id ON followings (season_id)');
        $this->addSql('CREATE INDEX followings_show_id ON followings (show_id)');
        $this->addSql('CREATE INDEX followings_start_date ON followings (start_date)');
        $this->addSql('CREATE INDEX followings_end_date ON followings (end_date)');
        $this->addSql('CREATE UNIQUE INDEX followings_user_episode_unique ON followings (user_id, episode_id)');
        $this->addSql('ALTER TABLE followings ADD CONSTRAINT FK_227CC344A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE followings ADD CONSTRAINT FK_227CC344362B62A0 FOREIGN KEY (episode_id) REFERENCES episodes (id)');
        $this->addSql('ALTER TABLE followings ADD CONSTRAINT FK_227CC3444EC001D1 FOREIGN KEY (season_id) REFERENCES seasons (id)');
        $this->addSql('ALTER TABLE followings ADD CONSTRAINT FK_227CC344D0C1FC64 FOREIGN KEY (show_id) REFERENCES tvshows (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE followings DROP CONSTRAINT FK_227CC344A76ED395');
        $this->addSql('ALTER TABLE followings DROP CONSTRAINT FK_227CC344362B62A0');
        $this->addSql('ALTER TABLE followings DROP CONSTRAINT FK_227CC3444EC001D1');
        $this->addSql('ALTER TABLE followings DROP CONSTRAINT FK_227CC344D0C1FC64');
        $this->addSql('DROP TABLE followings');
    }
}
