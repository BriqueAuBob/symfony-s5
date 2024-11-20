<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241120102551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content ADD thumbnail_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE content DROP thumbnail');
        $this->addSql('COMMENT ON COLUMN content.thumbnail_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9FDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES uploads (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEC530A9FDFF2E92 ON content (thumbnail_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE content DROP CONSTRAINT FK_FEC530A9FDFF2E92');
        $this->addSql('DROP INDEX UNIQ_FEC530A9FDFF2E92');
        $this->addSql('ALTER TABLE content ADD thumbnail TEXT NOT NULL');
        $this->addSql('ALTER TABLE content DROP thumbnail_id');
    }
}
