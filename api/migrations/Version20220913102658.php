<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220913102658 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE document_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE document_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE hackaton_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE document (id INT NOT NULL, hackaton_id INT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, file TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D8698A76B333DC5B ON document (hackaton_id)');
        $this->addSql('CREATE INDEX IDX_D8698A76C54C8C93 ON document (type_id)');
        $this->addSql('CREATE TABLE document_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE hackaton (id INT NOT NULL, name VARCHAR(255) NOT NULL, customer VARCHAR(255) NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76B333DC5B FOREIGN KEY (hackaton_id) REFERENCES hackaton (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76C54C8C93 FOREIGN KEY (type_id) REFERENCES document_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A76C54C8C93');
        $this->addSql('ALTER TABLE document DROP CONSTRAINT FK_D8698A76B333DC5B');
        $this->addSql('DROP SEQUENCE document_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE document_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE hackaton_id_seq CASCADE');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE document_type');
        $this->addSql('DROP TABLE hackaton');
    }
}
