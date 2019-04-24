<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180320173754 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_user_pixie_billing (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender enum(\'male\', \'female\')');
        $this->addSql('ALTER TABLE pxl_user_pixie ADD billing_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_user_pixie ADD CONSTRAINT FK_B6AE2DA43B025C87 FOREIGN KEY (billing_id) REFERENCES pxl_user_pixie_billing (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B6AE2DA43B025C87 ON pxl_user_pixie (billing_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user_pixie DROP FOREIGN KEY FK_B6AE2DA43B025C87');
        $this->addSql('DROP TABLE pxl_user_pixie_billing');
        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('DROP INDEX UNIQ_B6AE2DA43B025C87 ON pxl_user_pixie');
        $this->addSql('ALTER TABLE pxl_user_pixie DROP billing_id');
    }
}
