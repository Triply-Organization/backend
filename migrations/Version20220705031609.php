<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705031609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bill (id INT AUTO_INCREMENT NOT NULL, total_price DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION DEFAULT NULL, tax DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', currency VARCHAR(4) NOT NULL, stripe_payment_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE destination (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, discount_id INT DEFAULT NULL, bill_id INT DEFAULT NULL, tax_id INT DEFAULT NULL, total_price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), INDEX IDX_F52993984C7C611F (discount_id), UNIQUE INDEX UNIQ_F52993981A8C12F5 (bill_id), INDEX IDX_F5299398B2A824D8 (tax_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_list (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, schedule_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', currency VARCHAR(4) NOT NULL, INDEX IDX_399A0AA2C54C8C93 (type_id), INDEX IDX_399A0AA2A40BC2D5 (schedule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, order_detail_id INT NOT NULL, tour_id INT NOT NULL, comment VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_794381C6A76ED395 (user_id), UNIQUE INDEX UNIQ_794381C664577843 (order_detail_id), INDEX IDX_794381C615ED8D43 (tour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review_detail (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, review_id INT NOT NULL, rate DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A4703853C54C8C93 (type_id), INDEX IDX_A47038533E2E969B (review_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, tour_id INT NOT NULL, start_date DATE NOT NULL, ticket_remain INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5A3811FB15ED8D43 (tour_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tax (id INT AUTO_INCREMENT NOT NULL, currency VARCHAR(4) NOT NULL, percent INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, price_list_id INT NOT NULL, order_name_id INT NOT NULL, amount INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', total_price DOUBLE PRECISION NOT NULL, INDEX IDX_97A0ADA35688DED7 (price_list_id), INDEX IDX_97A0ADA3876D359D (order_name_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tour (id INT AUTO_INCREMENT NOT NULL, created_user_id INT NOT NULL, title VARCHAR(255) NOT NULL, duration INT NOT NULL, max_people INT NOT NULL, min_age INT NOT NULL, over_view VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, INDEX IDX_6AD1F969E104C1D3 (created_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tour_image (id INT AUTO_INCREMENT NOT NULL, tour_id INT NOT NULL, image_id INT NOT NULL, type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FE72147715ED8D43 (tour_id), UNIQUE INDEX UNIQ_FE7214773DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tour_plan (id INT AUTO_INCREMENT NOT NULL, tour_id INT NOT NULL, destination_id INT NOT NULL, day INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FC6ABB5D15ED8D43 (tour_id), INDEX IDX_FC6ABB5D816C6140 (destination_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tour_service (id INT AUTO_INCREMENT NOT NULL, tour_id INT NOT NULL, service_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A1771F5315ED8D43 (tour_id), INDEX IDX_A1771F53ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_review (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, avatar_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D64986383B10 (avatar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voucher (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, discount INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', remain INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984C7C611F FOREIGN KEY (discount_id) REFERENCES voucher (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993981A8C12F5 FOREIGN KEY (bill_id) REFERENCES bill (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B2A824D8 FOREIGN KEY (tax_id) REFERENCES tax (id)');
        $this->addSql('ALTER TABLE price_list ADD CONSTRAINT FK_399A0AA2C54C8C93 FOREIGN KEY (type_id) REFERENCES ticket_type (id)');
        $this->addSql('ALTER TABLE price_list ADD CONSTRAINT FK_399A0AA2A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C664577843 FOREIGN KEY (order_detail_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C615ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
        $this->addSql('ALTER TABLE review_detail ADD CONSTRAINT FK_A4703853C54C8C93 FOREIGN KEY (type_id) REFERENCES type_review (id)');
        $this->addSql('ALTER TABLE review_detail ADD CONSTRAINT FK_A47038533E2E969B FOREIGN KEY (review_id) REFERENCES review (id)');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB15ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA35688DED7 FOREIGN KEY (price_list_id) REFERENCES price_list (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3876D359D FOREIGN KEY (order_name_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE tour ADD CONSTRAINT FK_6AD1F969E104C1D3 FOREIGN KEY (created_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tour_image ADD CONSTRAINT FK_FE72147715ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
        $this->addSql('ALTER TABLE tour_image ADD CONSTRAINT FK_FE7214773DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE tour_plan ADD CONSTRAINT FK_FC6ABB5D15ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
        $this->addSql('ALTER TABLE tour_plan ADD CONSTRAINT FK_FC6ABB5D816C6140 FOREIGN KEY (destination_id) REFERENCES destination (id)');
        $this->addSql('ALTER TABLE tour_service ADD CONSTRAINT FK_A1771F5315ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
        $this->addSql('ALTER TABLE tour_service ADD CONSTRAINT FK_A1771F53ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64986383B10 FOREIGN KEY (avatar_id) REFERENCES image (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993981A8C12F5');
        $this->addSql('ALTER TABLE tour_plan DROP FOREIGN KEY FK_FC6ABB5D816C6140');
        $this->addSql('ALTER TABLE tour_image DROP FOREIGN KEY FK_FE7214773DA5256D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64986383B10');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C664577843');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3876D359D');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA35688DED7');
        $this->addSql('ALTER TABLE review_detail DROP FOREIGN KEY FK_A47038533E2E969B');
        $this->addSql('ALTER TABLE price_list DROP FOREIGN KEY FK_399A0AA2A40BC2D5');
        $this->addSql('ALTER TABLE tour_service DROP FOREIGN KEY FK_A1771F53ED5CA9E6');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B2A824D8');
        $this->addSql('ALTER TABLE price_list DROP FOREIGN KEY FK_399A0AA2C54C8C93');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C615ED8D43');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB15ED8D43');
        $this->addSql('ALTER TABLE tour_image DROP FOREIGN KEY FK_FE72147715ED8D43');
        $this->addSql('ALTER TABLE tour_plan DROP FOREIGN KEY FK_FC6ABB5D15ED8D43');
        $this->addSql('ALTER TABLE tour_service DROP FOREIGN KEY FK_A1771F5315ED8D43');
        $this->addSql('ALTER TABLE review_detail DROP FOREIGN KEY FK_A4703853C54C8C93');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE tour DROP FOREIGN KEY FK_6AD1F969E104C1D3');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993984C7C611F');
        $this->addSql('DROP TABLE bill');
        $this->addSql('DROP TABLE destination');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE price_list');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE review_detail');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE tax');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE ticket_type');
        $this->addSql('DROP TABLE tour');
        $this->addSql('DROP TABLE tour_image');
        $this->addSql('DROP TABLE tour_plan');
        $this->addSql('DROP TABLE tour_service');
        $this->addSql('DROP TABLE type_review');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE voucher');
    }
}
