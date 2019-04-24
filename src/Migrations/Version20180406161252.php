<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180406161252 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_card ADD CONSTRAINT FK_E4D00041896DBBDE FOREIGN KEY (updated_by_id) REFERENCES pxl_admin (id)');
        $this->addSql('CREATE INDEX IDX_E4D00041896DBBDE ON pxl_card (updated_by_id)');
        $this->addSql('ALTER TABLE pxl_card_project ADD card_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_card_project ADD CONSTRAINT FK_71A68ED04ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_71A68ED04ACC9A20 ON pxl_card_project (card_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card DROP FOREIGN KEY FK_E4D00041896DBBDE');
        $this->addSql('DROP INDEX IDX_E4D00041896DBBDE ON pxl_card');
        $this->addSql('ALTER TABLE pxl_card DROP updated_by_id');
        $this->addSql('ALTER TABLE pxl_card_project DROP FOREIGN KEY FK_71A68ED04ACC9A20');
        $this->addSql('DROP INDEX UNIQ_71A68ED04ACC9A20 ON pxl_card_project');
        $this->addSql('ALTER TABLE pxl_card_project DROP card_id');
    }
}
