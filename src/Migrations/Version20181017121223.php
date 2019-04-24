<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181017121223 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card ADD masterhead_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_card ADD CONSTRAINT FK_E4D00041FCC92133 FOREIGN KEY (masterhead_id) REFERENCES pxl_card_media (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E4D00041FCC92133 ON pxl_card (masterhead_id)');
        $this->addSql('ALTER TABLE pxl_user ADD visible TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_user_pixie CHANGE like_text like_text TEXT DEFAULT NULL, CHANGE dislike_text dislike_text TEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card DROP FOREIGN KEY FK_E4D00041FCC92133');
        $this->addSql('DROP INDEX UNIQ_E4D00041FCC92133 ON pxl_card');
        $this->addSql('ALTER TABLE pxl_card DROP masterhead_id');
        $this->addSql('ALTER TABLE pxl_user DROP visible');
        $this->addSql('ALTER TABLE pxl_user_pixie CHANGE like_text like_text TEXT NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE dislike_text dislike_text TEXT NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
