<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310212602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE education_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE education (id INT NOT NULL, summary_id INT DEFAULT NULL, type VARCHAR(64) NOT NULL, institution VARCHAR(255) DEFAULT NULL, faculty VARCHAR(150) DEFAULT NULL, specialization VARCHAR(150) DEFAULT NULL, graduation_year SMALLINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DB0A5ED22AC2D45C ON education (summary_id)');
        $this->addSql('ALTER TABLE education ADD CONSTRAINT FK_DB0A5ED22AC2D45C FOREIGN KEY (summary_id) REFERENCES summary (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE education_id_seq CASCADE');
        $this->addSql('ALTER TABLE education DROP CONSTRAINT FK_DB0A5ED22AC2D45C');
        $this->addSql('DROP TABLE education');
    }
}
