<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180405163332 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_card_project_info (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, INDEX IDX_F90E9678166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_card_project_info ADD CONSTRAINT FK_F90E9678166D1F9C FOREIGN KEY (project_id) REFERENCES pxl_card_project (id)');
        $this->addSql('ALTER TABLE pxl_card_info DROP FOREIGN KEY FK_44A4336D166D1F9C');
        $this->addSql('DROP INDEX IDX_44A4336D166D1F9C ON pxl_card_info');
        $this->addSql('ALTER TABLE pxl_card_info ADD value VARCHAR(255) NOT NULL, DROP project_id');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE pxl_card_project_info');
        $this->addSql('ALTER TABLE pxl_card_info ADD project_id INT DEFAULT NULL, DROP value');
        $this->addSql('ALTER TABLE pxl_card_info ADD CONSTRAINT FK_44A4336D166D1F9C FOREIGN KEY (project_id) REFERENCES pxl_card_project (id)');
        $this->addSql('CREATE INDEX IDX_44A4336D166D1F9C ON pxl_card_info (project_id)');
    }
}
