<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401235749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_stade ADD CONSTRAINT FK_C46921167D4819A FOREIGN KEY (id_stade) REFERENCES stade (id)');
        $this->addSql('CREATE INDEX IDX_C46921167D4819A ON images_stade (id_stade)');
        $this->addSql('ALTER TABLE stade DROP image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images_stade DROP FOREIGN KEY FK_C46921167D4819A');
        $this->addSql('DROP INDEX IDX_C46921167D4819A ON images_stade');
        $this->addSql('ALTER TABLE stade ADD image VARCHAR(255) NOT NULL');
    }
}
