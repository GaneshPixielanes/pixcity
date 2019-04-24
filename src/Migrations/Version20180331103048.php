<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180331103048 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card_project ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL, DROP created_by, DROP updated_by');
        $this->addSql('ALTER TABLE pxl_card_project ADD CONSTRAINT FK_71A68ED0B03A8386 FOREIGN KEY (created_by_id) REFERENCES pxl_admin (id)');
        $this->addSql('ALTER TABLE pxl_card_project ADD CONSTRAINT FK_71A68ED0896DBBDE FOREIGN KEY (updated_by_id) REFERENCES pxl_admin (id)');
        $this->addSql('CREATE INDEX IDX_71A68ED0B03A8386 ON pxl_card_project (created_by_id)');
        $this->addSql('CREATE INDEX IDX_71A68ED0896DBBDE ON pxl_card_project (updated_by_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card_project DROP FOREIGN KEY FK_71A68ED0B03A8386');
        $this->addSql('ALTER TABLE pxl_card_project DROP FOREIGN KEY FK_71A68ED0896DBBDE');
        $this->addSql('DROP INDEX IDX_71A68ED0B03A8386 ON pxl_card_project');
        $this->addSql('DROP INDEX IDX_71A68ED0896DBBDE ON pxl_card_project');
        $this->addSql('ALTER TABLE pxl_card_project ADD created_by VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD updated_by VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP created_by_id, DROP updated_by_id');
    }
}
