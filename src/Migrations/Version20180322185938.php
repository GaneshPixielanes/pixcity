<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180322185938 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_users_cardcategories (user_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_246DB33EA76ED395 (user_id), INDEX IDX_246DB33E12469DE2 (category_id), PRIMARY KEY(user_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_users_cardcategories ADD CONSTRAINT FK_246DB33EA76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
        $this->addSql('ALTER TABLE pxl_users_cardcategories ADD CONSTRAINT FK_246DB33E12469DE2 FOREIGN KEY (category_id) REFERENCES pxl_card_category (id)');
        $this->addSql('DROP TABLE user_card_category');
        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender enum(\'male\', \'female\')');
        $this->addSql('ALTER TABLE pxl_user_pixie_billing CHANGE billing_country billing_country VARCHAR(50) NOT NULL, CHANGE billing_iban billing_iban VARCHAR(40) NOT NULL, CHANGE billing_bic billing_bic VARCHAR(40) NOT NULL, CHANGE rib rib VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_card_category (user_id INT NOT NULL, card_category_id INT NOT NULL, INDEX IDX_51F37A12A76ED395 (user_id), INDEX IDX_51F37A12F592C278 (card_category_id), PRIMARY KEY(user_id, card_category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_card_category ADD CONSTRAINT FK_51F37A12A76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_card_category ADD CONSTRAINT FK_51F37A12F592C278 FOREIGN KEY (card_category_id) REFERENCES pxl_card_category (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE pxl_users_cardcategories');
        $this->addSql('ALTER TABLE pxl_user CHANGE gender gender VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE pxl_user_pixie_billing CHANGE billing_country billing_country VARCHAR(50) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE billing_iban billing_iban VARCHAR(40) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE billing_bic billing_bic VARCHAR(40) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE rib rib VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
    }
}
