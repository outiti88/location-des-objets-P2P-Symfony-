<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200519093448 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking CHANGE confirm confirm INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment_client DROP INDEX IDX_D575C47F675F31B, ADD UNIQUE INDEX UNIQ_D575C47F675F31B (author_id)');
        $this->addSql('ALTER TABLE comment_client DROP INDEX IDX_D575C4719EB6921, ADD UNIQUE INDEX UNIQ_D575C4719EB6921 (client_id)');
        $this->addSql('ALTER TABLE comment_client CHANGE author_id author_id INT NOT NULL, CHANGE client_id client_id INT NOT NULL');
        $this->addSql('ALTER TABLE premium CHANGE start_date start_date DATETIME DEFAULT NULL, CHANGE end_date end_date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE picture picture VARCHAR(255) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE booking CHANGE confirm confirm INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment_client DROP INDEX UNIQ_D575C47F675F31B, ADD INDEX IDX_D575C47F675F31B (author_id)');
        $this->addSql('ALTER TABLE comment_client DROP INDEX UNIQ_D575C4719EB6921, ADD INDEX IDX_D575C4719EB6921 (client_id)');
        $this->addSql('ALTER TABLE comment_client CHANGE author_id author_id INT DEFAULT NULL, CHANGE client_id client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE premium CHANGE start_date start_date DATETIME DEFAULT \'NULL\', CHANGE end_date end_date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE picture picture VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
