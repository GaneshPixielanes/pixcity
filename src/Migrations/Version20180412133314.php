<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180412133314 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_slider (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pxl_slider_media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pxl_slider_slide (id INT AUTO_INCREMENT NOT NULL, thumb_id INT DEFAULT NULL, background_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_FD54764EC7034EA5 (thumb_id), UNIQUE INDEX UNIQ_FD54764EC93D69EA (background_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_slider_slide ADD CONSTRAINT FK_FD54764EC7034EA5 FOREIGN KEY (thumb_id) REFERENCES pxl_card_media (id)');
        $this->addSql('ALTER TABLE pxl_slider_slide ADD CONSTRAINT FK_FD54764EC93D69EA FOREIGN KEY (background_id) REFERENCES pxl_card_media (id)');
        $this->addSql('ALTER TABLE pxl_page ADD slider_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_page ADD CONSTRAINT FK_E6CE2EB22CCC9638 FOREIGN KEY (slider_id) REFERENCES pxl_slider (id)');
        $this->addSql('CREATE INDEX IDX_E6CE2EB22CCC9638 ON pxl_page (slider_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_page DROP FOREIGN KEY FK_E6CE2EB22CCC9638');
        $this->addSql('DROP TABLE pxl_slider');
        $this->addSql('DROP TABLE pxl_slider_media');
        $this->addSql('DROP TABLE pxl_slider_slide');
        $this->addSql('DROP INDEX IDX_E6CE2EB22CCC9638 ON pxl_page');
        $this->addSql('ALTER TABLE pxl_page DROP slider_id');
    }
}
