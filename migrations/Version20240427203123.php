<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427203123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE citoyen CHANGE cin cin VARCHAR(8) NOT NULL');
        $this->addSql('ALTER TABLE projet ADD manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_50159CA9783E3463 ON projet (manager_id)');
        $this->addSql('ALTER TABLE user CHANGE role role VARCHAR(255) NOT NULL, CHANGE num_tel num_tel VARCHAR(8) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE citoyen CHANGE cin cin INT NOT NULL');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9783E3463');
        $this->addSql('DROP INDEX IDX_50159CA9783E3463 ON projet');
        $this->addSql('ALTER TABLE projet DROP manager_id');
        $this->addSql('ALTER TABLE user CHANGE role role VARCHAR(255) DEFAULT \'USER\' NOT NULL, CHANGE num_tel num_tel INT NOT NULL');
    }
}
