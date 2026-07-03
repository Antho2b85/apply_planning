<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260703130353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE navire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE creneau ADD navire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5FD840FD82 FOREIGN KEY (navire_id) REFERENCES navire (id)');
        $this->addSql('CREATE INDEX IDX_F9668B5FD840FD82 ON creneau (navire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE navire');
        $this->addSql('ALTER TABLE creneau DROP FOREIGN KEY FK_F9668B5FD840FD82');
        $this->addSql('DROP INDEX IDX_F9668B5FD840FD82 ON creneau');
        $this->addSql('ALTER TABLE creneau DROP navire_id');
    }
}
