<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121112801 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, matiere_id INTEGER NOT NULL, presentiel_id INTEGER NOT NULL, niveau_id INTEGER NOT NULL, status_annonce_id INTEGER NOT NULL, auteur_id INTEGER NOT NULL, titre VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, duree_en_min INTEGER NOT NULL, lieux VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_F65593E5F46CD258 ON annonce (matiere_id)');
        $this->addSql('CREATE INDEX IDX_F65593E51C6751E0 ON annonce (presentiel_id)');
        $this->addSql('CREATE INDEX IDX_F65593E5B3E9C81 ON annonce (niveau_id)');
        $this->addSql('CREATE INDEX IDX_F65593E589564FA9 ON annonce (status_annonce_id)');
        $this->addSql('CREATE INDEX IDX_F65593E560BB6FE6 ON annonce (auteur_id)');
        $this->addSql('CREATE TABLE commentaire (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, source_id INTEGER NOT NULL, destinataire_id INTEGER NOT NULL, contenu CLOB NOT NULL, note INTEGER DEFAULT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_67F068BC953C1C61 ON commentaire (source_id)');
        $this->addSql('CREATE INDEX IDX_67F068BCA4F84F6E ON commentaire (destinataire_id)');
        $this->addSql('CREATE TABLE creneau (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, annonce_id INTEGER NOT NULL, debut_at DATETIME NOT NULL, fin_at DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_F9668B5F8805AB2F ON creneau (annonce_id)');
        $this->addSql('CREATE TABLE matiere (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE message (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, source_id INTEGER NOT NULL, destinataire_id INTEGER NOT NULL, contenu CLOB NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE INDEX IDX_B6BD307F953C1C61 ON message (source_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FA4F84F6E ON message (destinataire_id)');
        $this->addSql('CREATE TABLE niveau (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE presentiel (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE relation_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE status_annonce (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE status_candidat (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, genre VARCHAR(255) DEFAULT NULL, date_de_naissance DATE NOT NULL, presentation CLOB DEFAULT NULL, etude_et_diplome CLOB DEFAULT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE user_relation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, source_id INTEGER NOT NULL, destinataire_id INTEGER NOT NULL, relation_type_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_8204A349953C1C61 ON user_relation (source_id)');
        $this->addSql('CREATE INDEX IDX_8204A349A4F84F6E ON user_relation (destinataire_id)');
        $this->addSql('CREATE INDEX IDX_8204A349DC379EE2 ON user_relation (relation_type_id)');
        $this->addSql('CREATE TABLE utilisateur_annonce (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, candidat_id INTEGER NOT NULL, annonce_id INTEGER NOT NULL, status_candidat_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_8C5E64778D0EB82 ON utilisateur_annonce (candidat_id)');
        $this->addSql('CREATE INDEX IDX_8C5E64778805AB2F ON utilisateur_annonce (annonce_id)');
        $this->addSql('CREATE INDEX IDX_8C5E6477C0A9E73 ON utilisateur_annonce (status_candidat_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
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
