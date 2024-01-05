<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240103165421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, name VARCHAR(40) NOT NULL, creat_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_annonce (contact_id INT NOT NULL, annonce_id INT NOT NULL, INDEX IDX_6C7AB264E7A1254A (contact_id), INDEX IDX_6C7AB2648805AB2F (annonce_id), PRIMARY KEY(contact_id, annonce_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_annonce ADD CONSTRAINT FK_6C7AB264E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_annonce ADD CONSTRAINT FK_6C7AB2648805AB2F FOREIGN KEY (annonce_id) REFERENCES annonce (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE image CHANGE legende legende VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact_annonce DROP FOREIGN KEY FK_6C7AB264E7A1254A');
        $this->addSql('ALTER TABLE contact_annonce DROP FOREIGN KEY FK_6C7AB2648805AB2F');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contact_annonce');
        $this->addSql('ALTER TABLE image CHANGE legende legende VARCHAR(255) DEFAULT NULL');
    }
}
