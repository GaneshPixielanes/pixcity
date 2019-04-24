<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180330121359 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_cards_projects_categories (project_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_5A6088166D1F9C (project_id), INDEX IDX_5A608812469DE2 (category_id), PRIMARY KEY(project_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_cards_projects_categories ADD CONSTRAINT FK_5A6088166D1F9C FOREIGN KEY (project_id) REFERENCES pxl_card_project (id)');
        $this->addSql('ALTER TABLE pxl_cards_projects_categories ADD CONSTRAINT FK_5A608812469DE2 FOREIGN KEY (category_id) REFERENCES pxl_card_category (id)');
        $this->addSql('ALTER TABLE pxl_card_project ADD description TEXT NOT NULL, ADD min_photos INT NOT NULL, ADD min_videos INT NOT NULL, ADD min_words INT NOT NULL, ADD delivery_date DATE NOT NULL, ADD comment TEXT NOT NULL');
        $this->addSql('ALTER TABLE pxl_card_info ADD project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pxl_card_info ADD CONSTRAINT FK_44A4336D166D1F9C FOREIGN KEY (project_id) REFERENCES pxl_card_project (id)');
        $this->addSql('CREATE INDEX IDX_44A4336D166D1F9C ON pxl_card_info (project_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE pxl_cards_projects_categories');
        $this->addSql('ALTER TABLE pxl_card_info DROP FOREIGN KEY FK_44A4336D166D1F9C');
        $this->addSql('DROP INDEX IDX_44A4336D166D1F9C ON pxl_card_info');
        $this->addSql('ALTER TABLE pxl_card_info DROP project_id');
        $this->addSql('ALTER TABLE pxl_card_project DROP description, DROP min_photos, DROP min_videos, DROP min_words, DROP delivery_date, DROP comment');
    }
}
