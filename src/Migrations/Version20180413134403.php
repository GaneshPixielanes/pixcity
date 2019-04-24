<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180413134403 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_card_walls_categories (wall_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_C3569731C33923F1 (wall_id), INDEX IDX_C356973112469DE2 (category_id), PRIMARY KEY(wall_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_card_walls_categories ADD CONSTRAINT FK_C3569731C33923F1 FOREIGN KEY (wall_id) REFERENCES pxl_card_wall (id)');
        $this->addSql('ALTER TABLE pxl_card_walls_categories ADD CONSTRAINT FK_C356973112469DE2 FOREIGN KEY (category_id) REFERENCES pxl_card_category (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE pxl_card_walls_categories');
    }
}
