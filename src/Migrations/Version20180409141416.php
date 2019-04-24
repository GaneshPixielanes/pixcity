<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180409141416 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_menu (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_8FC1A201989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pxl_menu_item (id INT AUTO_INCREMENT NOT NULL, page_id INT DEFAULT NULL, menu_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, blank TINYINT(1) NOT NULL, position INT NOT NULL, INDEX IDX_B44AD5C8C4663E4 (page_id), INDEX IDX_B44AD5C8CCD7E912 (menu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_menu_item ADD CONSTRAINT FK_B44AD5C8C4663E4 FOREIGN KEY (page_id) REFERENCES pxl_page (id)');
        $this->addSql('ALTER TABLE pxl_menu_item ADD CONSTRAINT FK_B44AD5C8CCD7E912 FOREIGN KEY (menu_id) REFERENCES pxl_menu (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_menu_item DROP FOREIGN KEY FK_B44AD5C8CCD7E912');
        $this->addSql('DROP TABLE pxl_menu');
        $this->addSql('DROP TABLE pxl_menu_item');
    }
}
