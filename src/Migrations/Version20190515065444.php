<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190515065444 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

//        $this->addSql('CREATE TABLE client_mission_proposal_pack (client_mission_proposal_id INT NOT NULL, pack_id INT NOT NULL, INDEX IDX_EC262DC88E3BE0B6 (client_mission_proposal_id), INDEX IDX_EC262DC81919B217 (pack_id), PRIMARY KEY(client_mission_proposal_id, pack_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE community_media (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, updated_by INT NOT NULL, INDEX IDX_115CDB48A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
//        $this->addSql('CREATE TABLE pxl_contact (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, message LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
//        $this->addSql('CREATE TABLE pxl_mission (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
//        $this->addSql('CREATE TABLE pxl_b2b_skills_users (skill_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9D6F9B825585C142 (skill_id), INDEX IDX_9D6F9B82A76ED395 (user_id), PRIMARY KEY(skill_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
//        $this->addSql('ALTER TABLE client_mission_proposal_pack ADD CONSTRAINT FK_EC262DC88E3BE0B6 FOREIGN KEY (client_mission_proposal_id) REFERENCES pxl_b2b_client_mission_proposal (id) ON DELETE CASCADE');
//        $this->addSql('ALTER TABLE client_mission_proposal_pack ADD CONSTRAINT FK_EC262DC81919B217 FOREIGN KEY (pack_id) REFERENCES pxl_b2b_pack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE community_media ADD CONSTRAINT FK_115CDB48A76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
        $this->addSql('ALTER TABLE pxl_b2b_skills_users ADD CONSTRAINT FK_9D6F9B825585C142 FOREIGN KEY (skill_id) REFERENCES pxl_b2b_skills (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pxl_b2b_skills_users ADD CONSTRAINT FK_9D6F9B82A76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id) ON DELETE CASCADE');
//        $this->addSql('DROP TABLE pxl_b2b_business');
//        $this->addSql('DROP TABLE skill');
//        $this->addSql('ALTER TABLE pxl_user CHANGE roles roles JSON NOT NULL, CHANGE ig_flag ig_flag TINYINT(1) DEFAULT NULL');
//        $this->addSql('ALTER TABLE pxl_user ADD CONSTRAINT FK_7F574EDBA0C0BE62 FOREIGN KEY (birth_location_id) REFERENCES pxl_region (id)');
//        $this->addSql('ALTER TABLE pxl_user ADD CONSTRAINT FK_7F574EDB86383B10 FOREIGN KEY (avatar_id) REFERENCES pxl_user_media (id)');
//        $this->addSql('ALTER TABLE pxl_user ADD CONSTRAINT FK_7F574EDB31F7C64C FOREIGN KEY (pixie_id) REFERENCES pxl_user_pixie (id)');
//        $this->addSql('ALTER TABLE pxl_user ADD CONSTRAINT FK_7F574EDBC6E97BAC FOREIGN KEY (optin_id) REFERENCES pxl_user_optin (id)');
//        $this->addSql('ALTER TABLE pxl_users_cardcategories ADD CONSTRAINT FK_246DB33EA76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
//        $this->addSql('ALTER TABLE pxl_users_cardcategories ADD CONSTRAINT FK_246DB33E12469DE2 FOREIGN KEY (category_id) REFERENCES pxl_card_category (id)');
//        $this->addSql('ALTER TABLE pxl_users_pixies ADD CONSTRAINT FK_1021243EA76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
//        $this->addSql('ALTER TABLE pxl_users_pixies ADD CONSTRAINT FK_1021243E31F7C64C FOREIGN KEY (pixie_id) REFERENCES pxl_user (id)');
//        $this->addSql('ALTER TABLE pxl_users_favorites ADD CONSTRAINT FK_8976DBEFA76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
//        $this->addSql('ALTER TABLE pxl_users_favorites ADD CONSTRAINT FK_8976DBEF4ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
//        $this->addSql('ALTER TABLE pxl_users_likes ADD CONSTRAINT FK_17258938A76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
//        $this->addSql('ALTER TABLE pxl_users_likes ADD CONSTRAINT FK_172589384ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
//        $this->addSql('ALTER TABLE pxl_b2b_regions_users ADD PRIMARY KEY (user_id, region_id)');
//        $this->addSql('ALTER TABLE pxl_b2b_regions_users ADD CONSTRAINT FK_1284BC95A76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id) ON DELETE CASCADE');
//        $this->addSql('ALTER TABLE pxl_b2b_regions_users ADD CONSTRAINT FK_1284BC9598260155 FOREIGN KEY (region_id) REFERENCES pxl_region (id) ON DELETE CASCADE');
//        $this->addSql('CREATE INDEX IDX_1284BC95A76ED395 ON pxl_b2b_regions_users (user_id)');
//        $this->addSql('CREATE INDEX IDX_1284BC9598260155 ON pxl_b2b_regions_users (region_id)');
//        $this->addSql('ALTER TABLE pxl_admin CHANGE roles roles JSON NOT NULL');
//        $this->addSql('ALTER TABLE pxl_card CHANGE gmb_flag gmb_flag SMALLINT DEFAULT NULL');
//        $this->addSql('ALTER TABLE pxl_card_project ADD CONSTRAINT FK_71A68ED098260155 FOREIGN KEY (region_id) REFERENCES pxl_region (id)');
//        $this->addSql('ALTER TABLE pxl_card_project ADD CONSTRAINT FK_71A68ED0AE80F5DF FOREIGN KEY (department_id) REFERENCES pxl_region_department (id)');
//        $this->addSql('ALTER TABLE pxl_card_project ADD CONSTRAINT FK_71A68ED031F7C64C FOREIGN KEY (pixie_id) REFERENCES pxl_user (id)');
//        $this->addSql('ALTER TABLE pxl_card_project ADD CONSTRAINT FK_71A68ED04ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
//        $this->addSql('ALTER TABLE pxl_card_project ADD CONSTRAINT FK_71A68ED0B03A8386 FOREIGN KEY (created_by_id) REFERENCES pxl_admin (id)');
//        $this->addSql('ALTER TABLE pxl_card_project ADD CONSTRAINT FK_71A68ED0896DBBDE FOREIGN KEY (updated_by_id) REFERENCES pxl_admin (id)');
//        $this->addSql('ALTER TABLE pxl_card_wall ADD CONSTRAINT FK_9CD8EDCC98260155 FOREIGN KEY (region_id) REFERENCES pxl_region (id)');
//        $this->addSql('ALTER TABLE pxl_card_wall ADD CONSTRAINT FK_9CD8EDCCAE80F5DF FOREIGN KEY (department_id) REFERENCES pxl_region_department (id)');
//        $this->addSql('ALTER TABLE pxl_card_walls_categories ADD CONSTRAINT FK_C3569731C33923F1 FOREIGN KEY (wall_id) REFERENCES pxl_card_wall (id)');
//        $this->addSql('ALTER TABLE pxl_card_walls_categories ADD CONSTRAINT FK_C356973112469DE2 FOREIGN KEY (category_id) REFERENCES pxl_card_category (id)');
//        $this->addSql('ALTER TABLE pxl_region CHANGE coordinates coordinates LONGTEXT DEFAULT NULL, CHANGE short_name short_name VARCHAR(15) DEFAULT NULL');
//        $this->addSql('ALTER TABLE pxl_region_department ADD CONSTRAINT FK_F7D1145998260155 FOREIGN KEY (region_id) REFERENCES pxl_region (id)');
//        $this->addSql('ALTER TABLE pxl_page_category ADD CONSTRAINT FK_FEDDD89798260155 FOREIGN KEY (region_id) REFERENCES pxl_region (id)');
//        $this->addSql('ALTER TABLE pxl_page_category ADD CONSTRAINT FK_FEDDD897C7034EA5 FOREIGN KEY (thumb_id) REFERENCES pxl_page_category_media (id)');
//        $this->addSql('ALTER TABLE pxl_page_category ADD CONSTRAINT FK_FEDDD897C93D69EA FOREIGN KEY (background_id) REFERENCES pxl_page_category_media (id)');
//        $this->addSql('ALTER TABLE pxl_page ADD CONSTRAINT FK_E6CE2EB22CCC9638 FOREIGN KEY (slider_id) REFERENCES pxl_slider (id)');
//        $this->addSql('ALTER TABLE pxl_slider_slide ADD CONSTRAINT FK_FD54764E2CCC9638 FOREIGN KEY (slider_id) REFERENCES pxl_slider (id)');
//        $this->addSql('ALTER TABLE pxl_slider_slide ADD CONSTRAINT FK_FD54764E3DA5256D FOREIGN KEY (image_id) REFERENCES pxl_slider_media (id)');
//        $this->addSql('ALTER TABLE pxl_slider_slide ADD CONSTRAINT FK_FD54764EC93D69EA FOREIGN KEY (background_id) REFERENCES pxl_slider_media (id)');
//        $this->addSql('ALTER TABLE pxl_transaction ADD CONSTRAINT FK_CBAF66DDA76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
//        $this->addSql('ALTER TABLE pxl_transactions_projects ADD CONSTRAINT FK_524F76012FC0CB0F FOREIGN KEY (transaction_id) REFERENCES pxl_transaction (id)');
//        $this->addSql('ALTER TABLE pxl_transactions_projects ADD CONSTRAINT FK_524F7601166D1F9C FOREIGN KEY (project_id) REFERENCES pxl_card_project (id)');
//        $this->addSql('ALTER TABLE pxl_gmb_card_details_api ADD CONSTRAINT FK_B26F7A954ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
//        $this->addSql('ALTER TABLE pxl_gmb_card_details_api RENAME INDEX uniq_f4deb4e54acc9a20 TO UNIQ_B26F7A954ACC9A20');
//        $this->addSql('ALTER TABLE pxl_card_info CHANGE value value VARCHAR(255) NOT NULL');
//        $this->addSql('ALTER TABLE pxl_card_info ADD CONSTRAINT FK_44A4336D4ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
//        $this->addSql('ALTER TABLE pxl_card_media ADD CONSTRAINT FK_ACAF55BC4ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id) ON DELETE CASCADE');
//        $this->addSql('ALTER TABLE pxl_card_project_attachment ADD CONSTRAINT FK_1AC392A2166D1F9C FOREIGN KEY (project_id) REFERENCES pxl_card_project (id)');
//        $this->addSql('ALTER TABLE pxl_card_project_info ADD CONSTRAINT FK_F90E9678166D1F9C FOREIGN KEY (project_id) REFERENCES pxl_card_project (id)');
//        $this->addSql('ALTER TABLE pxl_b2b_client CHANGE roles roles JSON NOT NULL');
//        $this->addSql('ALTER TABLE pxl_content_draft ADD CONSTRAINT FK_D5B104304ACC9A20 FOREIGN KEY (card_id) REFERENCES pxl_card (id)');
//        $this->addSql('ALTER TABLE pxl_content_draft ADD CONSTRAINT FK_D5B104306C1197C9 FOREIGN KEY (project_id_id) REFERENCES pxl_card_project (id)');
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_D5B104304ACC9A20 ON pxl_content_draft (card_id)');
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_D5B104306C1197C9 ON pxl_content_draft (project_id_id)');
//        $this->addSql('ALTER TABLE pxl_hashtag CHANGE created_at created_at DATETIME NOT NULL');
//        $this->addSql('ALTER TABLE pxl_instagram_trends ADD CONSTRAINT FK_19A980AFA76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
//        $this->addSql('ALTER TABLE pxl_instagram_trends RENAME INDEX idx_33cdc9faa76ed395 TO IDX_19A980AFA76ED395');
//        $this->addSql('ALTER TABLE pxl_menu_item ADD CONSTRAINT FK_B44AD5C8C4663E4 FOREIGN KEY (page_id) REFERENCES pxl_page (id)');
//        $this->addSql('ALTER TABLE pxl_menu_item ADD CONSTRAINT FK_B44AD5C8CCD7E912 FOREIGN KEY (menu_id) REFERENCES pxl_menu (id)');
//        $this->addSql('ALTER TABLE pxl_note ADD CONSTRAINT FK_3D796286514956FD FOREIGN KEY (collection_id) REFERENCES pxl_card_collection (id)');
//        $this->addSql('ALTER TABLE pxl_outbound_cm ADD CONSTRAINT FK_90B68ED748FCC97C FOREIGN KEY (cm_id) REFERENCES pxl_user (id)');
//        $this->addSql('ALTER TABLE pxl_outbound_cm ADD CONSTRAINT FK_90B68ED732A1827C FOREIGN KEY (end_user_id) REFERENCES pxl_user (id)');
//        $this->addSql('ALTER TABLE pxl_outbound_cm RENAME INDEX idx_1f720b6148fcc97c TO IDX_90B68ED748FCC97C');
//        $this->addSql('ALTER TABLE pxl_outbound_cm RENAME INDEX idx_1f720b6132a1827c TO IDX_90B68ED732A1827C');
//        $this->addSql('ALTER TABLE pxl_b2b_pack DROP INDEX UNIQ_9999FABD642B8210, ADD INDEX IDX_9999FABD642B8210 (admin_id)');
//        $this->addSql('ALTER TABLE pxl_b2b_skills CHANGE created_at created_at DATETIME NOT NULL, CHANGE updated_at updated_at DATETIME NOT NULL');
//        $this->addSql('ALTER TABLE pxl_static_pages CHANGE attributes attributes VARCHAR(255) DEFAULT NULL');
//        $this->addSql('ALTER TABLE pxl_transaction_history ADD CONSTRAINT FK_5003F1D52FC0CB0F FOREIGN KEY (transaction_id) REFERENCES pxl_transaction (id)');
//        $this->addSql('DROP INDEX UNIQ_CABD0A1E3DA5256D ON pxl_user_avatar');
//        $this->addSql('ALTER TABLE pxl_user_avatar DROP image_id');
//        $this->addSql('ALTER TABLE pxl_user_avatar ADD CONSTRAINT FK_CABD0A1EA76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
//        $this->addSql('ALTER TABLE pxl_user_avatar RENAME INDEX idx_73256912a76ed395 TO IDX_CABD0A1EA76ED395');
//        $this->addSql('ALTER TABLE pxl_user_instagram_details_api ADD CONSTRAINT FK_958B4C4FA76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
//        $this->addSql('ALTER TABLE pxl_user_instagram_details_api RENAME INDEX uniq_87eb7c8ca76ed395 TO UNIQ_958B4C4FA76ED395');
//        $this->addSql('ALTER TABLE pxl_user_link ADD CONSTRAINT FK_2F33D5A0A76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
//        $this->addSql('DROP INDEX IDX_76595F22A76ED395 ON pxl_user_media');
//        $this->addSql('ALTER TABLE pxl_user_media DROP user_id, CHANGE name name VARCHAR(100) NOT NULL');
//        $this->addSql('ALTER TABLE pxl_b2b_user_mission DROP INDEX UNIQ_ECE9393DA76ED395, ADD INDEX IDX_ECE9393DA76ED395 (user_id)');
//        $this->addSql('ALTER TABLE pxl_b2b_user_mission DROP INDEX UNIQ_ECE9393D1E6917BF, ADD INDEX IDX_ECE9393D1E6917BF (reference_pack_id)');
//        $this->addSql('ALTER TABLE pxl_b2b_user_mission DROP INDEX UNIQ_ECE9393D19EB6921, ADD INDEX IDX_ECE9393D19EB6921 (client_id)');
//        $this->addSql('ALTER TABLE pxl_b2b_user_mission ADD region_id INT DEFAULT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL, CHANGE dude_date due_date DATETIME DEFAULT NULL');
//        $this->addSql('ALTER TABLE pxl_b2b_user_mission ADD CONSTRAINT FK_ECE9393D98260155 FOREIGN KEY (region_id) REFERENCES pxl_region (id)');
//        $this->addSql('CREATE INDEX IDX_ECE9393D98260155 ON pxl_b2b_user_mission (region_id)');
//        $this->addSql('ALTER TABLE pxl_b2b_user_packs DROP INDEX UNIQ_F5AA9894A76ED395, ADD INDEX IDX_F5AA9894A76ED395 (user_id)');
//        $this->addSql('ALTER TABLE pxl_b2b_user_packs ADD pack_skill_id INT NOT NULL');
//        $this->addSql('ALTER TABLE pxl_b2b_user_packs ADD CONSTRAINT FK_F5AA98947B0A8553 FOREIGN KEY (pack_skill_id) REFERENCES pxl_b2b_skills (id)');
//        $this->addSql('CREATE INDEX IDX_F5AA98947B0A8553 ON pxl_b2b_user_packs (pack_skill_id)');
//        $this->addSql('ALTER TABLE pxl_user_pixie ADD CONSTRAINT FK_B6AE2DA43B025C87 FOREIGN KEY (billing_id) REFERENCES pxl_user_pixie_billing (id)');
//        $this->addSql('ALTER TABLE pxl_user_pixies_regions ADD CONSTRAINT FK_9C16506531F7C64C FOREIGN KEY (pixie_id) REFERENCES pxl_user_pixie (id)');
//        $this->addSql('ALTER TABLE pxl_user_pixies_regions ADD CONSTRAINT FK_9C16506598260155 FOREIGN KEY (region_id) REFERENCES pxl_region (id)');
//        $this->addSql('ALTER TABLE pxl_user_pixie_billing ADD CONSTRAINT FK_A49691D7F5B7AF75 FOREIGN KEY (address_id) REFERENCES pxl_address (id)');
//        $this->addSql('ALTER TABLE pxl_user_registration_check ADD CONSTRAINT FK_E5E7E963A76ED395 FOREIGN KEY (user_id) REFERENCES pxl_user (id)');
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_E5E7E963A76ED395 ON pxl_user_registration_check (user_id)');
    }

    public function down(Schema $schema) : void
    {
//        // this down() migration is auto-generated, please modify it to your needs
//        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
//
//        $this->addSql('CREATE TABLE pxl_b2b_business (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
//        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, created_by INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
//        $this->addSql('DROP TABLE client_mission_proposal_pack');
//        $this->addSql('DROP TABLE community_media');
//        $this->addSql('DROP TABLE pxl_contact');
//        $this->addSql('DROP TABLE pxl_mission');
//        $this->addSql('DROP TABLE pxl_b2b_skills_users');
//        $this->addSql('ALTER TABLE pxl_admin CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
//        $this->addSql('ALTER TABLE pxl_b2b_client CHANGE roles roles TEXT NOT NULL COLLATE utf8mb4_unicode_ci');
//        $this->addSql('ALTER TABLE pxl_b2b_pack DROP INDEX IDX_9999FABD642B8210, ADD UNIQUE INDEX UNIQ_9999FABD642B8210 (admin_id)');
//        $this->addSql('ALTER TABLE pxl_b2b_regions_users DROP FOREIGN KEY FK_1284BC95A76ED395');
//        $this->addSql('ALTER TABLE pxl_b2b_regions_users DROP FOREIGN KEY FK_1284BC9598260155');
//        $this->addSql('DROP INDEX IDX_1284BC95A76ED395 ON pxl_b2b_regions_users');
//        $this->addSql('DROP INDEX IDX_1284BC9598260155 ON pxl_b2b_regions_users');
//        $this->addSql('ALTER TABLE pxl_b2b_regions_users DROP PRIMARY KEY');
//        $this->addSql('ALTER TABLE pxl_b2b_skills CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
//        $this->addSql('ALTER TABLE pxl_b2b_user_mission DROP INDEX IDX_ECE9393DA76ED395, ADD UNIQUE INDEX UNIQ_ECE9393DA76ED395 (user_id)');
//        $this->addSql('ALTER TABLE pxl_b2b_user_mission DROP INDEX IDX_ECE9393D19EB6921, ADD UNIQUE INDEX UNIQ_ECE9393D19EB6921 (client_id)');
//        $this->addSql('ALTER TABLE pxl_b2b_user_mission DROP INDEX IDX_ECE9393D1E6917BF, ADD UNIQUE INDEX UNIQ_ECE9393D1E6917BF (reference_pack_id)');
//        $this->addSql('ALTER TABLE pxl_b2b_user_mission DROP FOREIGN KEY FK_ECE9393D98260155');
//        $this->addSql('DROP INDEX IDX_ECE9393D98260155 ON pxl_b2b_user_mission');
//        $this->addSql('ALTER TABLE pxl_b2b_user_mission DROP region_id, CHANGE updated_at updated_at DATETIME NOT NULL, CHANGE due_date dude_date DATETIME DEFAULT NULL');
//        $this->addSql('ALTER TABLE pxl_b2b_user_packs DROP INDEX IDX_F5AA9894A76ED395, ADD UNIQUE INDEX UNIQ_F5AA9894A76ED395 (user_id)');
//        $this->addSql('ALTER TABLE pxl_b2b_user_packs DROP FOREIGN KEY FK_F5AA98947B0A8553');
//        $this->addSql('DROP INDEX IDX_F5AA98947B0A8553 ON pxl_b2b_user_packs');
//        $this->addSql('ALTER TABLE pxl_b2b_user_packs DROP pack_skill_id');
//        $this->addSql('ALTER TABLE pxl_card CHANGE gmb_flag gmb_flag TINYINT(1) DEFAULT \'0\'');
//        $this->addSql('ALTER TABLE pxl_card_info DROP FOREIGN KEY FK_44A4336D4ACC9A20');
//        $this->addSql('ALTER TABLE pxl_card_info CHANGE value value VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
//        $this->addSql('ALTER TABLE pxl_card_media DROP FOREIGN KEY FK_ACAF55BC4ACC9A20');
//        $this->addSql('ALTER TABLE pxl_card_project DROP FOREIGN KEY FK_71A68ED098260155');
//        $this->addSql('ALTER TABLE pxl_card_project DROP FOREIGN KEY FK_71A68ED0AE80F5DF');
//        $this->addSql('ALTER TABLE pxl_card_project DROP FOREIGN KEY FK_71A68ED031F7C64C');
//        $this->addSql('ALTER TABLE pxl_card_project DROP FOREIGN KEY FK_71A68ED04ACC9A20');
//        $this->addSql('ALTER TABLE pxl_card_project DROP FOREIGN KEY FK_71A68ED0B03A8386');
//        $this->addSql('ALTER TABLE pxl_card_project DROP FOREIGN KEY FK_71A68ED0896DBBDE');
//        $this->addSql('ALTER TABLE pxl_card_project_attachment DROP FOREIGN KEY FK_1AC392A2166D1F9C');
//        $this->addSql('ALTER TABLE pxl_card_project_info DROP FOREIGN KEY FK_F90E9678166D1F9C');
//        $this->addSql('ALTER TABLE pxl_card_wall DROP FOREIGN KEY FK_9CD8EDCC98260155');
//        $this->addSql('ALTER TABLE pxl_card_wall DROP FOREIGN KEY FK_9CD8EDCCAE80F5DF');
//        $this->addSql('ALTER TABLE pxl_card_walls_categories DROP FOREIGN KEY FK_C3569731C33923F1');
//        $this->addSql('ALTER TABLE pxl_card_walls_categories DROP FOREIGN KEY FK_C356973112469DE2');
//        $this->addSql('ALTER TABLE pxl_content_draft DROP FOREIGN KEY FK_D5B104304ACC9A20');
//        $this->addSql('ALTER TABLE pxl_content_draft DROP FOREIGN KEY FK_D5B104306C1197C9');
//        $this->addSql('DROP INDEX UNIQ_D5B104304ACC9A20 ON pxl_content_draft');
//        $this->addSql('DROP INDEX UNIQ_D5B104306C1197C9 ON pxl_content_draft');
//        $this->addSql('ALTER TABLE pxl_gmb_card_details_api DROP FOREIGN KEY FK_B26F7A954ACC9A20');
//        $this->addSql('ALTER TABLE pxl_gmb_card_details_api RENAME INDEX uniq_b26f7a954acc9a20 TO UNIQ_F4DEB4E54ACC9A20');
//        $this->addSql('ALTER TABLE pxl_hashtag CHANGE created_at created_at DATETIME DEFAULT NULL');
//        $this->addSql('ALTER TABLE pxl_instagram_trends DROP FOREIGN KEY FK_19A980AFA76ED395');
//        $this->addSql('ALTER TABLE pxl_instagram_trends RENAME INDEX idx_19a980afa76ed395 TO IDX_33CDC9FAA76ED395');
//        $this->addSql('ALTER TABLE pxl_menu_item DROP FOREIGN KEY FK_B44AD5C8C4663E4');
//        $this->addSql('ALTER TABLE pxl_menu_item DROP FOREIGN KEY FK_B44AD5C8CCD7E912');
//        $this->addSql('ALTER TABLE pxl_note DROP FOREIGN KEY FK_3D796286514956FD');
//        $this->addSql('ALTER TABLE pxl_outbound_cm DROP FOREIGN KEY FK_90B68ED748FCC97C');
//        $this->addSql('ALTER TABLE pxl_outbound_cm DROP FOREIGN KEY FK_90B68ED732A1827C');
//        $this->addSql('ALTER TABLE pxl_outbound_cm RENAME INDEX idx_90b68ed748fcc97c TO IDX_1F720B6148FCC97C');
//        $this->addSql('ALTER TABLE pxl_outbound_cm RENAME INDEX idx_90b68ed732a1827c TO IDX_1F720B6132A1827C');
//        $this->addSql('ALTER TABLE pxl_page DROP FOREIGN KEY FK_E6CE2EB22CCC9638');
//        $this->addSql('ALTER TABLE pxl_page_category DROP FOREIGN KEY FK_FEDDD89798260155');
//        $this->addSql('ALTER TABLE pxl_page_category DROP FOREIGN KEY FK_FEDDD897C7034EA5');
//        $this->addSql('ALTER TABLE pxl_page_category DROP FOREIGN KEY FK_FEDDD897C93D69EA');
//        $this->addSql('ALTER TABLE pxl_region CHANGE coordinates coordinates INT NOT NULL, CHANGE short_name short_name TEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci');
//        $this->addSql('ALTER TABLE pxl_region_department DROP FOREIGN KEY FK_F7D1145998260155');
//        $this->addSql('ALTER TABLE pxl_slider_slide DROP FOREIGN KEY FK_FD54764E2CCC9638');
//        $this->addSql('ALTER TABLE pxl_slider_slide DROP FOREIGN KEY FK_FD54764E3DA5256D');
//        $this->addSql('ALTER TABLE pxl_slider_slide DROP FOREIGN KEY FK_FD54764EC93D69EA');
//        $this->addSql('ALTER TABLE pxl_static_pages CHANGE attributes attributes TEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci');
//        $this->addSql('ALTER TABLE pxl_transaction DROP FOREIGN KEY FK_CBAF66DDA76ED395');
//        $this->addSql('ALTER TABLE pxl_transaction_history DROP FOREIGN KEY FK_5003F1D52FC0CB0F');
//        $this->addSql('ALTER TABLE pxl_transactions_projects DROP FOREIGN KEY FK_524F76012FC0CB0F');
//        $this->addSql('ALTER TABLE pxl_transactions_projects DROP FOREIGN KEY FK_524F7601166D1F9C');
//        $this->addSql('ALTER TABLE pxl_user DROP FOREIGN KEY FK_7F574EDBA0C0BE62');
//        $this->addSql('ALTER TABLE pxl_user DROP FOREIGN KEY FK_7F574EDB86383B10');
//        $this->addSql('ALTER TABLE pxl_user DROP FOREIGN KEY FK_7F574EDB31F7C64C');
//        $this->addSql('ALTER TABLE pxl_user DROP FOREIGN KEY FK_7F574EDBC6E97BAC');
//        $this->addSql('ALTER TABLE pxl_user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin, CHANGE ig_flag ig_flag TINYINT(1) DEFAULT \'0\'');
//        $this->addSql('ALTER TABLE pxl_user_avatar DROP FOREIGN KEY FK_CABD0A1EA76ED395');
//        $this->addSql('ALTER TABLE pxl_user_avatar ADD image_id INT DEFAULT NULL');
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_CABD0A1E3DA5256D ON pxl_user_avatar (image_id)');
//        $this->addSql('ALTER TABLE pxl_user_avatar RENAME INDEX idx_cabd0a1ea76ed395 TO IDX_73256912A76ED395');
//        $this->addSql('ALTER TABLE pxl_user_instagram_details_api DROP FOREIGN KEY FK_958B4C4FA76ED395');
//        $this->addSql('ALTER TABLE pxl_user_instagram_details_api RENAME INDEX uniq_958b4c4fa76ed395 TO UNIQ_87EB7C8CA76ED395');
//        $this->addSql('ALTER TABLE pxl_user_link DROP FOREIGN KEY FK_2F33D5A0A76ED395');
//        $this->addSql('ALTER TABLE pxl_user_media ADD user_id INT DEFAULT NULL, CHANGE name name VARCHAR(100) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
//        $this->addSql('CREATE INDEX IDX_76595F22A76ED395 ON pxl_user_media (user_id)');
//        $this->addSql('ALTER TABLE pxl_user_pixie DROP FOREIGN KEY FK_B6AE2DA43B025C87');
//        $this->addSql('ALTER TABLE pxl_user_pixie_billing DROP FOREIGN KEY FK_A49691D7F5B7AF75');
//        $this->addSql('ALTER TABLE pxl_user_pixies_regions DROP FOREIGN KEY FK_9C16506531F7C64C');
//        $this->addSql('ALTER TABLE pxl_user_pixies_regions DROP FOREIGN KEY FK_9C16506598260155');
//        $this->addSql('ALTER TABLE pxl_user_registration_check DROP FOREIGN KEY FK_E5E7E963A76ED395');
//        $this->addSql('DROP INDEX UNIQ_E5E7E963A76ED395 ON pxl_user_registration_check');
//        $this->addSql('ALTER TABLE pxl_users_cardcategories DROP FOREIGN KEY FK_246DB33EA76ED395');
//        $this->addSql('ALTER TABLE pxl_users_cardcategories DROP FOREIGN KEY FK_246DB33E12469DE2');
//        $this->addSql('ALTER TABLE pxl_users_favorites DROP FOREIGN KEY FK_8976DBEFA76ED395');
//        $this->addSql('ALTER TABLE pxl_users_favorites DROP FOREIGN KEY FK_8976DBEF4ACC9A20');
//        $this->addSql('ALTER TABLE pxl_users_likes DROP FOREIGN KEY FK_17258938A76ED395');
//        $this->addSql('ALTER TABLE pxl_users_likes DROP FOREIGN KEY FK_172589384ACC9A20');
//        $this->addSql('ALTER TABLE pxl_users_pixies DROP FOREIGN KEY FK_1021243EA76ED395');
//        $this->addSql('ALTER TABLE pxl_users_pixies DROP FOREIGN KEY FK_1021243E31F7C64C');
    }
}
