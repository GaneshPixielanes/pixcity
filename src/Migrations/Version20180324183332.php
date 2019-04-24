<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180324183332 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_region (id INT AUTO_INCREMENT NOT NULL, position INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B2C1F05C989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pxl_user_pixies_regions (pixie_id INT NOT NULL, region_id INT NOT NULL, INDEX IDX_9C16506531F7C64C (pixie_id), INDEX IDX_9C16506598260155 (region_id), PRIMARY KEY(pixie_id, region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_user_pixies_regions ADD CONSTRAINT FK_9C16506531F7C64C FOREIGN KEY (pixie_id) REFERENCES pxl_user_pixie (id)');
        $this->addSql('ALTER TABLE pxl_user_pixies_regions ADD CONSTRAINT FK_9C16506598260155 FOREIGN KEY (region_id) REFERENCES pxl_region (id)');
        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender enum(\'male\', \'female\')');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user_pixies_regions DROP FOREIGN KEY FK_9C16506598260155');
        $this->addSql('DROP TABLE pxl_region');
        $this->addSql('DROP TABLE pxl_user_pixies_regions');
        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
