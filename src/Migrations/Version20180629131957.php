<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180629131957 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user ADD birth_location_id INT DEFAULT NULL, DROP birth_location');
        $this->addSql('ALTER TABLE pxl_user ADD CONSTRAINT FK_7F574EDBA0C0BE62 FOREIGN KEY (birth_location_id) REFERENCES pxl_region (id)');
        $this->addSql('CREATE INDEX IDX_7F574EDBA0C0BE62 ON pxl_user (birth_location_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user DROP FOREIGN KEY FK_7F574EDBA0C0BE62');
        $this->addSql('DROP INDEX IDX_7F574EDBA0C0BE62 ON pxl_user');
        $this->addSql('ALTER TABLE pxl_user ADD birth_location VARCHAR(16) NOT NULL COLLATE utf8mb4_unicode_ci, DROP birth_location_id');
    }
}
