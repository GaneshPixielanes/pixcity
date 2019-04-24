<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180411115813 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card_collections_cards DROP FOREIGN KEY FK_FFD99AB5A76ED395');
        $this->addSql('DROP INDEX IDX_FFD99AB5A76ED395 ON pxl_card_collections_cards');
        $this->addSql('ALTER TABLE pxl_card_collections_cards DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pxl_card_collections_cards CHANGE user_id collection_id INT NOT NULL');
        $this->addSql('ALTER TABLE pxl_card_collections_cards ADD CONSTRAINT FK_FFD99AB5514956FD FOREIGN KEY (collection_id) REFERENCES pxl_card_collection (id)');
        $this->addSql('CREATE INDEX IDX_FFD99AB5514956FD ON pxl_card_collections_cards (collection_id)');
        $this->addSql('ALTER TABLE pxl_card_collections_cards ADD PRIMARY KEY (collection_id, card_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card_collections_cards DROP FOREIGN KEY FK_FFD99AB5514956FD');
        $this->addSql('DROP INDEX IDX_FFD99AB5514956FD ON pxl_card_collections_cards');
        $this->addSql('ALTER TABLE pxl_card_collections_cards DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pxl_card_collections_cards CHANGE collection_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE pxl_card_collections_cards ADD CONSTRAINT FK_FFD99AB5A76ED395 FOREIGN KEY (user_id) REFERENCES pxl_card_collection (id)');
        $this->addSql('CREATE INDEX IDX_FFD99AB5A76ED395 ON pxl_card_collections_cards (user_id)');
        $this->addSql('ALTER TABLE pxl_card_collections_cards ADD PRIMARY KEY (user_id, card_id)');
    }
}
