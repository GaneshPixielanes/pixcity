<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180405120141 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_card (id INT AUTO_INCREMENT NOT NULL, region_id INT DEFAULT NULL, department_id INT DEFAULT NULL, pixie_id INT DEFAULT NULL, status VARCHAR(50) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, meta_title VARCHAR(255) NOT NULL, meta_description VARCHAR(255) NOT NULL, content TEXT NOT NULL, indexed TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E4D00041989D9B62 (slug), INDEX IDX_E4D0004198260155 (region_id), INDEX IDX_E4D00041AE80F5DF (department_id), INDEX IDX_E4D0004131F7C64C (pixie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pxl_cards_categories (card_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_893336A64ACC9A20 (card_id), INDEX IDX_893336A612469DE2 (category_id), PRIMARY KEY(card_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pxl_card_media (id INT AUTO_INCREMENT NOT NULL, card_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, INDEX IDX_ACAF55BC4ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_card ADD CONSTRAINT FK_E4D0004198260155 FOREIGN KEY (region_id) REFERENCES pxl_region (id)');
        $this->addSql('ALTER TABLE pxl_card ADD CONSTRAINT FK_E4D00041AE80F5DF FOREIGN KEY (department_id) REFERENCES pxl_region_department (id)');
        $this->addSql('ALTER TABLE pxl_card ADD CONSTRAINT FK_E4D0004131F7C64C FOREIGN KEY (pixie_id) REFERENCES pxl_user (id)');
        $this->addSql('ALTER TABLE pxl_cards_categories ADD CONSTRAINT FK_893336A64ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
        $this->addSql('ALTER TABLE pxl_cards_categories ADD CONSTRAINT FK_893336A612469DE2 FOREIGN KEY (category_id) REFERENCES pxl_card_category (id)');
        $this->addSql('ALTER TABLE pxl_card_media ADD CONSTRAINT FK_ACAF55BC4ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
        $this->addSql('ALTER TABLE pxl_card_info ADD card_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_card_info ADD CONSTRAINT FK_44A4336D4ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
        $this->addSql('CREATE INDEX IDX_44A4336D4ACC9A20 ON pxl_card_info (card_id)');
        $this->addSql('ALTER TABLE pxl_card_project CHANGE status status VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_cards_categories DROP FOREIGN KEY FK_893336A64ACC9A20');
        $this->addSql('ALTER TABLE pxl_card_info DROP FOREIGN KEY FK_44A4336D4ACC9A20');
        $this->addSql('ALTER TABLE pxl_card_media DROP FOREIGN KEY FK_ACAF55BC4ACC9A20');
        $this->addSql('DROP TABLE pxl_card');
        $this->addSql('DROP TABLE pxl_cards_categories');
        $this->addSql('DROP TABLE pxl_card_media');
        $this->addSql('DROP INDEX IDX_44A4336D4ACC9A20 ON pxl_card_info');
        $this->addSql('ALTER TABLE pxl_card_info DROP card_id');
        $this->addSql('ALTER TABLE pxl_card_project CHANGE status status VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
