<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180406202107 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card ADD thumb_id INT DEFAULT NULL, DROP thumb');
        $this->addSql('ALTER TABLE pxl_card ADD CONSTRAINT FK_E4D00041C7034EA5 FOREIGN KEY (thumb_id) REFERENCES pxl_card_media (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E4D00041C7034EA5 ON pxl_card (thumb_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card DROP FOREIGN KEY FK_E4D00041C7034EA5');
        $this->addSql('DROP INDEX UNIQ_E4D00041C7034EA5 ON pxl_card');
        $this->addSql('ALTER TABLE pxl_card ADD thumb VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, DROP thumb_id');
    }
}
