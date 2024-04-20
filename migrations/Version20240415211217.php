<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240415211217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tache_commentaires (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, tache_projet_id INT DEFAULT NULL, content VARCHAR(500) NOT NULL, INDEX IDX_4849A5F4A76ED395 (user_id), INDEX IDX_4849A5F458120F72 (tache_projet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tache_commentaires ADD CONSTRAINT FK_4849A5F4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tache_commentaires ADD CONSTRAINT FK_4849A5F458120F72 FOREIGN KEY (tache_projet_id) REFERENCES tache_projet (id)');
        $this->addSql('ALTER TABLE projet_user DROP FOREIGN KEY FK_FA413966C18272');
        $this->addSql('ALTER TABLE projet_user DROP FOREIGN KEY FK_FA413966A76ED395');
        $this->addSql('ALTER TABLE projet_user ADD CONSTRAINT FK_FA413966C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_user ADD CONSTRAINT FK_FA413966A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache_projet DROP FOREIGN KEY FK_BCE9A358C18272');
        $this->addSql('ALTER TABLE tache_projet DROP FOREIGN KEY FK_BCE9A358A76ED395');
        $this->addSql('ALTER TABLE tache_projet ADD CONSTRAINT FK_BCE9A358C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE tache_projet ADD CONSTRAINT FK_BCE9A358A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64943787BBA');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64943787BBA FOREIGN KEY (citoyen_id) REFERENCES citoyen (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tache_commentaires DROP FOREIGN KEY FK_4849A5F4A76ED395');
        $this->addSql('ALTER TABLE tache_commentaires DROP FOREIGN KEY FK_4849A5F458120F72');
        $this->addSql('DROP TABLE tache_commentaires');
        $this->addSql('ALTER TABLE projet_user DROP FOREIGN KEY FK_FA413966C18272');
        $this->addSql('ALTER TABLE projet_user DROP FOREIGN KEY FK_FA413966A76ED395');
        $this->addSql('ALTER TABLE projet_user ADD CONSTRAINT FK_FA413966C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_user ADD CONSTRAINT FK_FA413966A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache_projet DROP FOREIGN KEY FK_BCE9A358C18272');
        $this->addSql('ALTER TABLE tache_projet DROP FOREIGN KEY FK_BCE9A358A76ED395');
        $this->addSql('ALTER TABLE tache_projet ADD CONSTRAINT FK_BCE9A358C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache_projet ADD CONSTRAINT FK_BCE9A358A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64943787BBA');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64943787BBA FOREIGN KEY (citoyen_id) REFERENCES citoyen (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
