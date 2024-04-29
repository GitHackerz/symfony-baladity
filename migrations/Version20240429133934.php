<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429133934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FD8521CCA76ED395 ON association (user_id)');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9783E3463');
        $this->addSql('DROP INDEX IDX_50159CA9783E3463 ON projet');
        $this->addSql('ALTER TABLE projet DROP manager_id');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404A76ED395');
        $this->addSql('DROP INDEX IDX_CE606404A76ED395 ON reclamation');
        $this->addSql('ALTER TABLE reclamation ADD type VARCHAR(255) NOT NULL, DROP user_id, DROP titre, DROP priorite, DROP image, CHANGE date date VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reponse_reclamation DROP contenu');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CCA76ED395');
        $this->addSql('DROP INDEX IDX_FD8521CCA76ED395 ON association');
        $this->addSql('ALTER TABLE association DROP user_id');
        $this->addSql('ALTER TABLE projet ADD manager_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_50159CA9783E3463 ON projet (manager_id)');
        $this->addSql('ALTER TABLE reclamation ADD user_id INT DEFAULT NULL, ADD priorite VARCHAR(255) NOT NULL, ADD image VARCHAR(255) NOT NULL, CHANGE date date DATETIME NOT NULL, CHANGE type titre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CE606404A76ED395 ON reclamation (user_id)');
        $this->addSql('ALTER TABLE reponse_reclamation ADD contenu VARCHAR(255) NOT NULL');
    }
}
