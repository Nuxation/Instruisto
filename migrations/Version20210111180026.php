<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210111180026 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, matiere_id INT NOT NULL, presentiel_id INT NOT NULL, niveau_id INT NOT NULL, status_annonce_id INT NOT NULL, auteur_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, duree_en_min INT NOT NULL, lieux VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_F65593E5F46CD258 (matiere_id), INDEX IDX_F65593E51C6751E0 (presentiel_id), INDEX IDX_F65593E5B3E9C81 (niveau_id), INDEX IDX_F65593E589564FA9 (status_annonce_id), INDEX IDX_F65593E560BB6FE6 (auteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, source_id INT NOT NULL, destinataire_id INT NOT NULL, contenu LONGTEXT NOT NULL, note INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_67F068BC953C1C61 (source_id), INDEX IDX_67F068BCA4F84F6E (destinataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE creneau (id INT AUTO_INCREMENT NOT NULL, annonce_id INT NOT NULL, debut_at DATETIME NOT NULL, fin_at DATETIME NOT NULL, INDEX IDX_F9668B5F8805AB2F (annonce_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, source_id INT NOT NULL, destinataire_id INT NOT NULL, contenu LONGTEXT NOT NULL, INDEX IDX_B6BD307F953C1C61 (source_id), INDEX IDX_B6BD307FA4F84F6E (destinataire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE presentiel (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation_type (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status_annonce (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status_candidat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, mot_de_pass VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, genre VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_relation (id INT AUTO_INCREMENT NOT NULL, source_id INT NOT NULL, destinataire_id INT NOT NULL, relation_type_id INT NOT NULL, INDEX IDX_8204A349953C1C61 (source_id), INDEX IDX_8204A349A4F84F6E (destinataire_id), INDEX IDX_8204A349DC379EE2 (relation_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur_annonce (id INT AUTO_INCREMENT NOT NULL, candidat_id INT NOT NULL, annonce_id INT NOT NULL, status_candidat_id INT NOT NULL, INDEX IDX_8C5E64778D0EB82 (candidat_id), INDEX IDX_8C5E64778805AB2F (annonce_id), INDEX IDX_8C5E6477C0A9E73 (status_candidat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E51C6751E0 FOREIGN KEY (presentiel_id) REFERENCES presentiel (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E589564FA9 FOREIGN KEY (status_annonce_id) REFERENCES status_annonce (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E560BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC953C1C61 FOREIGN KEY (source_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE creneau ADD CONSTRAINT FK_F9668B5F8805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F953C1C61 FOREIGN KEY (source_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA4F84F6E FOREIGN KEY (destinataire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_relation ADD CONSTRAINT FK_8204A349953C1C61 FOREIGN KEY (source_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_relation ADD CONSTRAINT FK_8204A349A4F84F6E FOREIGN KEY (destinataire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_relation ADD CONSTRAINT FK_8204A349DC379EE2 FOREIGN KEY (relation_type_id) REFERENCES relation_type (id)');
        $this->addSql('ALTER TABLE utilisateur_annonce ADD CONSTRAINT FK_8C5E64778D0EB82 FOREIGN KEY (candidat_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE utilisateur_annonce ADD CONSTRAINT FK_8C5E64778805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id)');
        $this->addSql('ALTER TABLE utilisateur_annonce ADD CONSTRAINT FK_8C5E6477C0A9E73 FOREIGN KEY (status_candidat_id) REFERENCES status_candidat (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE creneau DROP FOREIGN KEY FK_F9668B5F8805AB2F');
        $this->addSql('ALTER TABLE utilisateur_annonce DROP FOREIGN KEY FK_8C5E64778805AB2F');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5F46CD258');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5B3E9C81');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E51C6751E0');
        $this->addSql('ALTER TABLE user_relation DROP FOREIGN KEY FK_8204A349DC379EE2');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E589564FA9');
        $this->addSql('ALTER TABLE utilisateur_annonce DROP FOREIGN KEY FK_8C5E6477C0A9E73');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E560BB6FE6');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC953C1C61');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA4F84F6E');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F953C1C61');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA4F84F6E');
        $this->addSql('ALTER TABLE user_relation DROP FOREIGN KEY FK_8204A349953C1C61');
        $this->addSql('ALTER TABLE user_relation DROP FOREIGN KEY FK_8204A349A4F84F6E');
        $this->addSql('ALTER TABLE utilisateur_annonce DROP FOREIGN KEY FK_8C5E64778D0EB82');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE creneau');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE presentiel');
        $this->addSql('DROP TABLE relation_type');
        $this->addSql('DROP TABLE status_annonce');
        $this->addSql('DROP TABLE status_candidat');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_relation');
        $this->addSql('DROP TABLE utilisateur_annonce');
    }
}
