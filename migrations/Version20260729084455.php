<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260729084455 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prevision_du_jour (id INT AUTO_INCREMENT NOT NULL, agents_reserves VARCHAR(255) DEFAULT NULL, total_remorques INT DEFAULT NULL, embarque INT DEFAULT NULL, titres INT DEFAULT NULL, titres_total INT DEFAULT NULL, attentes INT DEFAULT NULL, agents_controle VARCHAR(255) DEFAULT NULL, total_passagers INT DEFAULT NULL, autos_basses INT DEFAULT NULL, hauteurs INT DEFAULT NULL, attelages INT DEFAULT NULL, motos INT DEFAULT NULL, controles INT DEFAULT NULL, a_venir INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE prevision_du_jour');
    }
}
