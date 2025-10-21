<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Uid\Uuid;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251021091045 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'added roles in database';
    }

    public function up(Schema $schema): void
    {
        $adminRoleId = Uuid::v7()->toRfc4122();
        $userRoleId = Uuid::v7()->toRfc4122();

        $this->addSql('INSERT INTO roles (id, label, code) VALUES (\''.$adminRoleId.'\'::uuid, \'ROLE_ADMIN\', \'ROLE_ADMIN\')');
        $this->addSql('INSERT INTO roles (id, label, code) VALUES (\''.$userRoleId.'\'::uuid, \'ROLE_USER\', \'ROLE_USER\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
