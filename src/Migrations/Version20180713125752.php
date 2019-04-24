<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180713125752 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_user_optin (id INT AUTO_INCREMENT NOT NULL, newsletter TINYINT(1) DEFAULT NULL, pixie_card_project_received TINYINT(1) DEFAULT NULL, pixie_card_status_updated TINYINT(1) DEFAULT NULL, last_cards_published TINYINT(1) DEFAULT NULL, last_cards_published_favorites_regions TINYINT(1) DEFAULT NULL, cards_of_the_month TINYINT(1) DEFAULT NULL, my_pixies_activity TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_user ADD optin_id INT DEFAULT NULL, DROP optin');
        $this->addSql('ALTER TABLE pxl_user ADD CONSTRAINT FK_7F574EDBC6E97BAC FOREIGN KEY (optin_id) REFERENCES pxl_user_optin (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7F574EDBC6E97BAC ON pxl_user (optin_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_user DROP FOREIGN KEY FK_7F574EDBC6E97BAC');
        $this->addSql('DROP TABLE pxl_user_optin');
        $this->addSql('DROP INDEX UNIQ_7F574EDBC6E97BAC ON pxl_user');
        $this->addSql('ALTER TABLE pxl_user ADD optin TINYINT(1) DEFAULT NULL, DROP optin_id');
    }
}
