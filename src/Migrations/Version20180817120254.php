<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180817120254 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_users_likes (user_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_17258938A76ED395 (user_id), INDEX IDX_172589384ACC9A20 (card_id), PRIMARY KEY(user_id, card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_users_likes ADD CONSTRAINT FK_17258938A76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
        $this->addSql('ALTER TABLE pxl_users_likes ADD CONSTRAINT FK_172589384ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE pxl_users_likes');
    }
}
