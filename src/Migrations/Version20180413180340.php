<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180413180340 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_page_category_media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_page_category ADD thumb_id INT DEFAULT NULL, ADD background_id INT DEFAULT NULL, ADD discover VARCHAR(255) NOT NULL, ADD facebook VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE pxl_page_category ADD CONSTRAINT FK_FEDDD897C7034EA5 FOREIGN KEY (thumb_id) REFERENCES pxl_page_category_media (id)');
        $this->addSql('ALTER TABLE pxl_page_category ADD CONSTRAINT FK_FEDDD897C93D69EA FOREIGN KEY (background_id) REFERENCES pxl_page_category_media (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEDDD897C7034EA5 ON pxl_page_category (thumb_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FEDDD897C93D69EA ON pxl_page_category (background_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_page_category DROP FOREIGN KEY FK_FEDDD897C7034EA5');
        $this->addSql('ALTER TABLE pxl_page_category DROP FOREIGN KEY FK_FEDDD897C93D69EA');
        $this->addSql('DROP TABLE pxl_page_category_media');
        $this->addSql('DROP INDEX UNIQ_FEDDD897C7034EA5 ON pxl_page_category');
        $this->addSql('DROP INDEX UNIQ_FEDDD897C93D69EA ON pxl_page_category');
        $this->addSql('ALTER TABLE pxl_page_category DROP thumb_id, DROP background_id, DROP discover, DROP facebook');
    }
}
