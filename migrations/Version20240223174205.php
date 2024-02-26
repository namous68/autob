<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223174205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE professionnel ADD garage_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE professionnel ADD CONSTRAINT FK_7A28C10FC4FFF555 FOREIGN KEY (garage_id) REFERENCES garage (id)');
        $this->addSql('CREATE INDEX IDX_7A28C10FC4FFF555 ON professionnel (garage_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE professionnel DROP FOREIGN KEY FK_7A28C10FC4FFF555');
        $this->addSql('DROP INDEX IDX_7A28C10FC4FFF555 ON professionnel');
        $this->addSql('ALTER TABLE professionnel DROP garage_id');
    }
}
