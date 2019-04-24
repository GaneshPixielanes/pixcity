<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180407102623 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card_media DROP FOREIGN KEY FK_ACAF55BC4ACC9A20');
        $this->addSql('ALTER TABLE pxl_card_media ADD CONSTRAINT FK_ACAF55BC4ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card_media DROP FOREIGN KEY FK_ACAF55BC4ACC9A20');
        $this->addSql('ALTER TABLE pxl_card_media ADD CONSTRAINT FK_ACAF55BC4ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
    }
}
