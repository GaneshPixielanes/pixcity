<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180413131524 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_card_wall (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, department_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, meta_title VARCHAR(255) NOT NULL, meta_description VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, indexed TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_9CD8EDCC989D9B62 (slug), INDEX IDX_9CD8EDCC98260155 (region_id), INDEX IDX_9CD8EDCCAE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_card_wall ADD CONSTRAINT FK_9CD8EDCC98260155 FOREIGN KEY (region_id) REFERENCES pxl_region (id)');
        $this->addSql('ALTER TABLE pxl_card_wall ADD CONSTRAINT FK_9CD8EDCCAE80F5DF FOREIGN KEY (department_id) REFERENCES pxl_region_department (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE pxl_card_wall');
    }
}
