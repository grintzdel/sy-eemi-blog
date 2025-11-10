<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251110141631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        // Add columns as nullable first
        $this->addSql('ALTER TABLE articles ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE articles ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE articles ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');

        // Set default values for existing rows
        $this->addSql('UPDATE articles SET created_at = NOW(), updated_at = NOW() WHERE created_at IS NULL');

        // Make created_at and updated_at NOT NULL
        $this->addSql('ALTER TABLE articles ALTER COLUMN created_at SET NOT NULL');
        $this->addSql('ALTER TABLE articles ALTER COLUMN updated_at SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles DROP created_at');
        $this->addSql('ALTER TABLE articles DROP updated_at');
        $this->addSql('ALTER TABLE articles DROP deleted_at');
    }
}
