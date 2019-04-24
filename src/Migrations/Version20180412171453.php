<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180412171453 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_slider_slide DROP FOREIGN KEY FK_FD54764E3DA5256D');
        $this->addSql('ALTER TABLE pxl_slider_slide DROP FOREIGN KEY FK_FD54764EC93D69EA');
        $this->addSql('ALTER TABLE pxl_slider_slide ADD CONSTRAINT FK_FD54764E3DA5256D FOREIGN KEY (image_id) REFERENCES pxl_slider_media (id)');
        $this->addSql('ALTER TABLE pxl_slider_slide ADD CONSTRAINT FK_FD54764EC93D69EA FOREIGN KEY (background_id) REFERENCES pxl_slider_media (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_slider_slide DROP FOREIGN KEY FK_FD54764E3DA5256D');
        $this->addSql('ALTER TABLE pxl_slider_slide DROP FOREIGN KEY FK_FD54764EC93D69EA');
        $this->addSql('ALTER TABLE pxl_slider_slide ADD CONSTRAINT FK_FD54764E3DA5256D FOREIGN KEY (image_id) REFERENCES pxl_card_media (id)');
        $this->addSql('ALTER TABLE pxl_slider_slide ADD CONSTRAINT FK_FD54764EC93D69EA FOREIGN KEY (background_id) REFERENCES pxl_card_media (id)');
    }
}
