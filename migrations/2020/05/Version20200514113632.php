<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200514113632 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE baby_log_line (
          id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', 
          baby_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', 
          creation_datetime DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', 
          type_id SMALLINT NOT NULL, 
          data LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', 
          INDEX IDX_D87B83162E288954 (baby_id), 
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE 
          baby_log_line 
        ADD 
          CONSTRAINT FK_D87B83162E288954 FOREIGN KEY (baby_id) REFERENCES baby (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE baby_log_line');
    }
}
