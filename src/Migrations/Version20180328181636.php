<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180328181636 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card_project ADD pixie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_card_project ADD CONSTRAINT FK_71A68ED031F7C64C FOREIGN KEY (pixie_id) REFERENCES pxl_user (id)');
        $this->addSql('CREATE INDEX IDX_71A68ED031F7C64C ON pxl_card_project (pixie_id)');
        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender enum(\'male\', \'female\')');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card_project DROP FOREIGN KEY FK_71A68ED031F7C64C');
        $this->addSql('DROP INDEX IDX_71A68ED031F7C64C ON pxl_card_project');
        $this->addSql('ALTER TABLE pxl_card_project DROP pixie_id');
        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
