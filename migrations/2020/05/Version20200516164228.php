<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200516164228 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE baby ADD user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\' AFTER id');
        $this->addSql('ALTER TABLE baby ADD CONSTRAINT FK_876C813EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_876C813EA76ED395 ON baby (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE baby DROP FOREIGN KEY FK_876C813EA76ED395');
        $this->addSql('DROP INDEX IDX_876C813EA76ED395 ON baby');
        $this->addSql('ALTER TABLE baby DROP user_id');
    }
}
