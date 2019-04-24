<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180320140050 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user ADD pixie_id INT DEFAULT NULL, CHANGE gender gender enum(\'male\', \'female\')');
        $this->addSql('ALTER TABLE pxl_user ADD CONSTRAINT FK_7F574EDB31F7C64C FOREIGN KEY (pixie_id) REFERENCES pxl_user_pixie (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7F574EDB31F7C64C ON pxl_user (pixie_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user DROP FOREIGN KEY FK_7F574EDB31F7C64C');
        $this->addSql('DROP INDEX UNIQ_7F574EDB31F7C64C ON pxl_user');
        $this->addSql('ALTER TABLE pxl_user DROP pixie_id, CHANGE gender gender VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
