<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260630135701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_creneau (id BINARY(16) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, user_id BINARY(16) NOT NULL, creneau_id BINARY(16) NOT NULL, INDEX IDX_A16B9555A76ED395 (user_id), INDEX IDX_A16B95557D0729A9 (creneau_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_creneau ADD CONSTRAINT FK_A16B9555A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_creneau ADD CONSTRAINT FK_A16B95557D0729A9 FOREIGN KEY (creneau_id) REFERENCES creneau (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_creneau DROP FOREIGN KEY FK_A16B9555A76ED395');
        $this->addSql('ALTER TABLE user_creneau DROP FOREIGN KEY FK_A16B95557D0729A9');
        $this->addSql('DROP TABLE user_creneau');
    }
}
