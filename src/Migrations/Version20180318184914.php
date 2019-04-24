<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180318184914 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender enum(\'male\', \'female\')');
        $this->addSql('ALTER TABLE pxl_user_link DROP FOREIGN KEY FK_2F33D5A09D86650F');
        $this->addSql('DROP INDEX IDX_2F33D5A09D86650F ON pxl_user_link');
        $this->addSql('ALTER TABLE pxl_user_link CHANGE user_id_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_user_link ADD CONSTRAINT FK_2F33D5A0A76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
        $this->addSql('CREATE INDEX IDX_2F33D5A0A76ED395 ON pxl_user_link (user_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE pxl_user_link DROP FOREIGN KEY FK_2F33D5A0A76ED395');
        $this->addSql('DROP INDEX IDX_2F33D5A0A76ED395 ON pxl_user_link');
        $this->addSql('ALTER TABLE pxl_user_link CHANGE user_id user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_user_link ADD CONSTRAINT FK_2F33D5A09D86650F FOREIGN KEY (user_id_id) REFERENCES pxl_user (id)');
        $this->addSql('CREATE INDEX IDX_2F33D5A09D86650F ON pxl_user_link (user_id_id)');
    }
}
