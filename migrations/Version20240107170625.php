<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240107170625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce ADD controle_technique VARCHAR(255) NOT NULL, ADD premiere_main VARCHAR(255) NOT NULL, ADD couleur VARCHAR(255) NOT NULL, ADD nombre_de_portes VARCHAR(255) NOT NULL, ADD volume_de_coffre VARCHAR(255) NOT NULL, ADD rechargeable VARCHAR(255) NOT NULL, ADD puissance_fiscale VARCHAR(255) DEFAULT NULL, ADD garantie VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP controle_technique, DROP premiere_main, DROP couleur, DROP nombre_de_portes, DROP volume_de_coffre, DROP rechargeable, DROP puissance_fiscale, DROP garantie');
    }
}
