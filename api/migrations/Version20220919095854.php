<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220919095854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hackaton_user (hackaton_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(hackaton_id, user_id))');
        $this->addSql('CREATE INDEX IDX_32D4698CB333DC5B ON hackaton_user (hackaton_id)');
        $this->addSql('CREATE INDEX IDX_32D4698CA76ED395 ON hackaton_user (user_id)');
        $this->addSql('ALTER TABLE hackaton_user ADD CONSTRAINT FK_32D4698CB333DC5B FOREIGN KEY (hackaton_id) REFERENCES hackaton (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hackaton_user ADD CONSTRAINT FK_32D4698CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE hackaton_user');
    }
}
