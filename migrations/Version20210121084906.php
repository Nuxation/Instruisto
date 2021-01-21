<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210121084906 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce CHANGE presentiel_id presentiel_id INT DEFAULT NULL, CHANGE niveau_id niveau_id INT DEFAULT NULL, CHANGE status_annonce_id status_annonce_id INT DEFAULT NULL, CHANGE auteur_id auteur_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce CHANGE presentiel_id presentiel_id INT NOT NULL, CHANGE niveau_id niveau_id INT NOT NULL, CHANGE status_annonce_id status_annonce_id INT NOT NULL, CHANGE auteur_id auteur_id INT NOT NULL');
    }
}
