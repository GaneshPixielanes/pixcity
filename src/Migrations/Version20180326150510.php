<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180326150510 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_card_info (id INT AUTO_INCREMENT NOT NULL, template_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, INDEX IDX_44A4336D5DA0FB8 (template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_card_info ADD CONSTRAINT FK_44A4336D5DA0FB8 FOREIGN KEY (template_id) REFERENCES pxl_card_template (id)');
        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender enum(\'male\', \'female\')');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE pxl_card_info');
        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
