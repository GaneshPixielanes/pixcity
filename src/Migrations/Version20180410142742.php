<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180410142742 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_users_favorites (user_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_8976DBEFA76ED395 (user_id), INDEX IDX_8976DBEF4ACC9A20 (card_id), PRIMARY KEY(user_id, card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pxl_card_collection (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, public TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_9B5D39C7989D9B62 (slug), INDEX IDX_9B5D39C7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pxl_card_collections_cards (user_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_FFD99AB5A76ED395 (user_id), INDEX IDX_FFD99AB54ACC9A20 (card_id), PRIMARY KEY(user_id, card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_users_favorites ADD CONSTRAINT FK_8976DBEFA76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
        $this->addSql('ALTER TABLE pxl_users_favorites ADD CONSTRAINT FK_8976DBEF4ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
        $this->addSql('ALTER TABLE pxl_card_collection ADD CONSTRAINT FK_9B5D39C7A76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
        $this->addSql('ALTER TABLE pxl_card_collections_cards ADD CONSTRAINT FK_FFD99AB5A76ED395 FOREIGN KEY (user_id) REFERENCES pxl_card_collection (id)');
        $this->addSql('ALTER TABLE pxl_card_collections_cards ADD CONSTRAINT FK_FFD99AB54ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_card_collections_cards DROP FOREIGN KEY FK_FFD99AB5A76ED395');
        $this->addSql('DROP TABLE pxl_users_favorites');
        $this->addSql('DROP TABLE pxl_card_collection');
        $this->addSql('DROP TABLE pxl_card_collections_cards');
    }
}
