<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200603184828 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE map_user_baby (
          user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', 
          baby_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', 
          INDEX IDX_58858AAAA76ED395 (user_id), 
          INDEX IDX_58858AAA2E288954 (baby_id), 
          PRIMARY KEY(user_id, baby_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE 
          map_user_baby 
        ADD 
          CONSTRAINT FK_58858AAAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE 
          map_user_baby 
        ADD 
          CONSTRAINT FK_58858AAA2E288954 FOREIGN KEY (baby_id) REFERENCES baby (id) ON DELETE CASCADE');
        $this->addSql('INSERT INTO map_user_baby SELECT user_id, id FROM baby');
        $this->addSql('ALTER TABLE baby DROP FOREIGN KEY FK_876C813EA76ED395');
        $this->addSql('DROP INDEX IDX_876C813EA76ED395 ON baby');
        $this->addSql('ALTER TABLE baby DROP user_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE map_user_baby');
        $this->addSql('ALTER TABLE 
          baby 
        ADD 
          user_id CHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE baby ADD CONSTRAINT FK_876C813EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_876C813EA76ED395 ON baby (user_id)');
    }
}
