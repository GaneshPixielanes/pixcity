<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180415085548 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE pxl_transaction (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, type VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_CBAF66DDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pxl_transactions_projects (transaction_id INT NOT NULL, project_id INT NOT NULL, INDEX IDX_524F76012FC0CB0F (transaction_id), INDEX IDX_524F7601166D1F9C (project_id), PRIMARY KEY(transaction_id, project_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pxl_transaction ADD CONSTRAINT FK_CBAF66DDA76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
        $this->addSql('ALTER TABLE pxl_transactions_projects ADD CONSTRAINT FK_524F76012FC0CB0F FOREIGN KEY (transaction_id) REFERENCES pxl_transaction (id)');
        $this->addSql('ALTER TABLE pxl_transactions_projects ADD CONSTRAINT FK_524F7601166D1F9C FOREIGN KEY (project_id) REFERENCES pxl_card_project (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pxl_transactions_projects DROP FOREIGN KEY FK_524F76012FC0CB0F');
        $this->addSql('DROP TABLE pxl_transaction');
        $this->addSql('DROP TABLE pxl_transactions_projects');
    }
}
