<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180406140336 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user_pixie_billing ADD address_id INT DEFAULT NULL, DROP address, DROP zipcode, DROP city, DROP country');
        $this->addSql('ALTER TABLE pxl_user_pixie_billing ADD CONSTRAINT FK_A49691D7F5B7AF75 FOREIGN KEY (address_id) REFERENCES pxl_address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A49691D7F5B7AF75 ON pxl_user_pixie_billing (address_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user_pixie_billing DROP FOREIGN KEY FK_A49691D7F5B7AF75');
        $this->addSql('DROP INDEX UNIQ_A49691D7F5B7AF75 ON pxl_user_pixie_billing');
        $this->addSql('ALTER TABLE pxl_user_pixie_billing ADD address VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, ADD zipcode VARCHAR(16) NOT NULL COLLATE utf8mb4_unicode_ci, ADD city VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, ADD country VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, DROP address_id');
    }
}
