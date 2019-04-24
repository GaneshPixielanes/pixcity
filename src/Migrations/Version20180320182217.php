<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180320182217 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender enum(\'male\', \'female\')');
        $this->addSql('ALTER TABLE pxl_user_pixie_billing ADD company_name VARCHAR(50) NOT NULL, ADD firstname VARCHAR(50) NOT NULL, ADD lastname VARCHAR(50) NOT NULL, ADD tva VARCHAR(20) NOT NULL, ADD address VARCHAR(50) NOT NULL, ADD zipcode VARCHAR(16) NOT NULL, ADD city VARCHAR(50) NOT NULL, ADD country VARCHAR(50) NOT NULL, ADD phone VARCHAR(16) NOT NULL, ADD billing_type VARCHAR(20) NOT NULL, ADD billing_name VARCHAR(100) NOT NULL, ADD billing_country VARCHAR(50) DEFAULT NULL, ADD billing_iban VARCHAR(40) DEFAULT NULL, ADD billing_bic VARCHAR(40) DEFAULT NULL, ADD rib VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE pxl_user_pixie_billing DROP company_name, DROP firstname, DROP lastname, DROP tva, DROP address, DROP zipcode, DROP city, DROP country, DROP phone, DROP billing_type, DROP billing_name, DROP billing_country, DROP billing_iban, DROP billing_bic, DROP rib');
    }
}
