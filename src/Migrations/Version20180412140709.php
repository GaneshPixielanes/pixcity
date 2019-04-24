<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180412140709 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_slider_slide ADD slider_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_slider_slide ADD CONSTRAINT FK_FD54764E2CCC9638 FOREIGN KEY (slider_id) REFERENCES pxl_slider (id)');
        $this->addSql('CREATE INDEX IDX_FD54764E2CCC9638 ON pxl_slider_slide (slider_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_slider_slide DROP FOREIGN KEY FK_FD54764E2CCC9638');
        $this->addSql('DROP INDEX IDX_FD54764E2CCC9638 ON pxl_slider_slide');
        $this->addSql('ALTER TABLE pxl_slider_slide DROP slider_id');
    }
}
