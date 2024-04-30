<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429215349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CCA76ED395');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_association DROP FOREIGN KEY FK_5CA27EB8A76ED395');
        $this->addSql('ALTER TABLE demande_association ADD CONSTRAINT FK_5CA27EB8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_document DROP FOREIGN KEY FK_9E30C3B4A76ED395');
        $this->addSql('ALTER TABLE demande_document DROP FOREIGN KEY FK_9E30C3B4C33F7837');
        $this->addSql('ALTER TABLE demande_document ADD CONSTRAINT FK_9E30C3B4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demande_document ADD CONSTRAINT FK_9E30C3B4C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB2971F7E88B');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB2971F7E88B FOREIGN KEY (event_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9783E3463');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9783E3463 FOREIGN KEY (manager_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache_commentaires DROP FOREIGN KEY FK_4849A5F458120F72');
        $this->addSql('ALTER TABLE tache_commentaires DROP FOREIGN KEY FK_4849A5F4A76ED395');
        $this->addSql('ALTER TABLE tache_commentaires ADD CONSTRAINT FK_4849A5F458120F72 FOREIGN KEY (tache_projet_id) REFERENCES tache_projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache_commentaires ADD CONSTRAINT FK_4849A5F4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache_projet DROP FOREIGN KEY FK_BCE9A358A76ED395');
        $this->addSql('ALTER TABLE tache_projet DROP FOREIGN KEY FK_BCE9A358C18272');
        $this->addSql('ALTER TABLE tache_projet ADD CONSTRAINT FK_BCE9A358A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tache_projet ADD CONSTRAINT FK_BCE9A358C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE association DROP FOREIGN KEY FK_FD8521CCA76ED395');
        $this->addSql('ALTER TABLE association ADD CONSTRAINT FK_FD8521CCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_association DROP FOREIGN KEY FK_5CA27EB8A76ED395');
        $this->addSql('ALTER TABLE demande_association ADD CONSTRAINT FK_5CA27EB8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_document DROP FOREIGN KEY FK_9E30C3B4A76ED395');
        $this->addSql('ALTER TABLE demande_document DROP FOREIGN KEY FK_9E30C3B4C33F7837');
        $this->addSql('ALTER TABLE demande_document ADD CONSTRAINT FK_9E30C3B4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE demande_document ADD CONSTRAINT FK_9E30C3B4C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB2971F7E88B');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB2971F7E88B FOREIGN KEY (event_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9783E3463');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9783E3463 FOREIGN KEY (manager_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tache_commentaires DROP FOREIGN KEY FK_4849A5F4A76ED395');
        $this->addSql('ALTER TABLE tache_commentaires DROP FOREIGN KEY FK_4849A5F458120F72');
        $this->addSql('ALTER TABLE tache_commentaires ADD CONSTRAINT FK_4849A5F4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tache_commentaires ADD CONSTRAINT FK_4849A5F458120F72 FOREIGN KEY (tache_projet_id) REFERENCES tache_projet (id)');
        $this->addSql('ALTER TABLE tache_projet DROP FOREIGN KEY FK_BCE9A358C18272');
        $this->addSql('ALTER TABLE tache_projet DROP FOREIGN KEY FK_BCE9A358A76ED395');
        $this->addSql('ALTER TABLE tache_projet ADD CONSTRAINT FK_BCE9A358C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE tache_projet ADD CONSTRAINT FK_BCE9A358A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }
}
