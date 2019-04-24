<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180405174526 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_address (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(50) NOT NULL, zipcode VARCHAR(16) NOT NULL, city VARCHAR(50) NOT NULL, country VARCHAR(50) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_card ADD address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_card ADD CONSTRAINT FK_E4D00041F5B7AF75 FOREIGN KEY (address_id) REFERENCES pxl_address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E4D00041F5B7AF75 ON pxl_card (address_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card DROP FOREIGN KEY FK_E4D00041F5B7AF75');
        $this->addSql('DROP TABLE pxl_address');
        $this->addSql('DROP INDEX UNIQ_E4D00041F5B7AF75 ON pxl_card');
        $this->addSql('ALTER TABLE pxl_card DROP address_id');
    }
}
