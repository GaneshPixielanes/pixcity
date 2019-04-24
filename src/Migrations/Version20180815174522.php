<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180815174522 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_users_pixies (user_id INT NOT NULL, pixie_id INT NOT NULL, INDEX IDX_1021243EA76ED395 (user_id), INDEX IDX_1021243E31F7C64C (pixie_id), PRIMARY KEY(user_id, pixie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_users_pixies ADD CONSTRAINT FK_1021243EA76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
        $this->addSql('ALTER TABLE pxl_users_pixies ADD CONSTRAINT FK_1021243E31F7C64C FOREIGN KEY (pixie_id) REFERENCES pxl_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE pxl_users_pixies');
    }
}
