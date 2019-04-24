<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180404154417 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card_info DROP FOREIGN KEY FK_44A4336D5DA0FB8');
        $this->addSql('DROP TABLE pxl_card_template');
        $this->addSql('DROP INDEX IDX_44A4336D5DA0FB8 ON pxl_card_info');
        $this->addSql('ALTER TABLE pxl_card_info DROP template_id');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_card_template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_card_info ADD template_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_card_info ADD CONSTRAINT FK_44A4336D5DA0FB8 FOREIGN KEY (template_id) REFERENCES pxl_card_template (id)');
        $this->addSql('CREATE INDEX IDX_44A4336D5DA0FB8 ON pxl_card_info (template_id)');
    }
}
