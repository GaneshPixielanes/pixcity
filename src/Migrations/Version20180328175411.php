<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180328175411 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card_project ADD region_id INT DEFAULT NULL, ADD department_id INT DEFAULT NULL, ADD status VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE pxl_card_project ADD CONSTRAINT FK_71A68ED098260155 FOREIGN KEY (region_id) REFERENCES pxl_region (id)');
        $this->addSql('ALTER TABLE pxl_card_project ADD CONSTRAINT FK_71A68ED0AE80F5DF FOREIGN KEY (department_id) REFERENCES pxl_region_department (id)');
        $this->addSql('CREATE INDEX IDX_71A68ED098260155 ON pxl_card_project (region_id)');
        $this->addSql('CREATE INDEX IDX_71A68ED0AE80F5DF ON pxl_card_project (department_id)');
        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender enum(\'male\', \'female\')');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card_project DROP FOREIGN KEY FK_71A68ED098260155');
        $this->addSql('ALTER TABLE pxl_card_project DROP FOREIGN KEY FK_71A68ED0AE80F5DF');
        $this->addSql('DROP INDEX IDX_71A68ED098260155 ON pxl_card_project');
        $this->addSql('DROP INDEX IDX_71A68ED0AE80F5DF ON pxl_card_project');
        $this->addSql('ALTER TABLE pxl_card_project DROP region_id, DROP department_id, DROP status');
        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
