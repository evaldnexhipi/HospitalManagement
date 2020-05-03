<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200502221128 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule_client (schedule_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_A565404AA40BC2D5 (schedule_id), INDEX IDX_A565404A19EB6921 (client_id), PRIMARY KEY(schedule_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schedule_medical_staff (schedule_id INT NOT NULL, medical_staff_id INT NOT NULL, INDEX IDX_1F5935A3A40BC2D5 (schedule_id), INDEX IDX_1F5935A371D7BA9A (medical_staff_id), PRIMARY KEY(schedule_id, medical_staff_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE results (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, medical_staff_id INT NOT NULL, analysis_pdf VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9FA3E41419EB6921 (client_id), INDEX IDX_9FA3E41471D7BA9A (medical_staff_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hall (id INT AUTO_INCREMENT NOT NULL, departament_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_1B8FA83F48B3EEE4 (departament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departament (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, medical_staff_id INT NOT NULL, service_id INT NOT NULL, status VARCHAR(15) NOT NULL, day DATE NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_42C8495519EB6921 (client_id), INDEX IDX_42C8495571D7BA9A (medical_staff_id), INDEX IDX_42C84955ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, email VARCHAR(60) DEFAULT NULL, name VARCHAR(20) NOT NULL, surname VARCHAR(20) NOT NULL, gender VARCHAR(1) NOT NULL, birthday DATE NOT NULL, address VARCHAR(255) DEFAULT NULL, tel VARCHAR(15) DEFAULT NULL, diagnosis VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1ADAD7EB54177093 (room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medical_item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, status TINYINT(1) NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, brochure_filename VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE items_location (id INT AUTO_INCREMENT NOT NULL, medical_item_id INT NOT NULL, INDEX IDX_29E53905546DB290 (medical_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE items_location_hall (items_location_id INT NOT NULL, hall_id INT NOT NULL, INDEX IDX_E8066DDFBE6E9CBF (items_location_id), INDEX IDX_E8066DDF52AFCFD6 (hall_id), PRIMARY KEY(items_location_id, hall_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE items_location_room (items_location_id INT NOT NULL, room_id INT NOT NULL, INDEX IDX_8116947BBE6E9CBF (items_location_id), INDEX IDX_8116947B54177093 (room_id), PRIMARY KEY(items_location_id, room_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE speciality (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) DEFAULT NULL, title VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_C7440455A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE treatment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, departament_id INT NOT NULL, name VARCHAR(50) NOT NULL, capacity INT DEFAULT NULL, status TINYINT(1) NOT NULL, INDEX IDX_729F519B48B3EEE4 (departament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, departament_id INT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, cost INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E19D9AD248B3EEE4 (departament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, gender VARCHAR(1) NOT NULL, birthday DATE NOT NULL, address VARCHAR(255) DEFAULT NULL, telephone VARCHAR(15) DEFAULT NULL, is_active TINYINT(1) NOT NULL, token VARCHAR(255) DEFAULT NULL, image_filename VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medical_staff (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, hall_id INT NOT NULL, speciality_id INT NOT NULL, status TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_7788A8C2A76ED395 (user_id), INDEX IDX_7788A8C252AFCFD6 (hall_id), INDEX IDX_7788A8C23B5A08D7 (speciality_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medical_staff_service (medical_staff_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_60030C8671D7BA9A (medical_staff_id), INDEX IDX_60030C86ED5CA9E6 (service_id), PRIMARY KEY(medical_staff_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE anamnesis (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, medical_staff_id INT NOT NULL, treatment_id INT DEFAULT NULL, diagnosis VARCHAR(255) NOT NULL, symptoms VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F0A5806919EB6921 (client_id), INDEX IDX_F0A5806971D7BA9A (medical_staff_id), INDEX IDX_F0A58069471C0366 (treatment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE schedule_client ADD CONSTRAINT FK_A565404AA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE schedule_client ADD CONSTRAINT FK_A565404A19EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE schedule_medical_staff ADD CONSTRAINT FK_1F5935A3A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE schedule_medical_staff ADD CONSTRAINT FK_1F5935A371D7BA9A FOREIGN KEY (medical_staff_id) REFERENCES medical_staff (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE results ADD CONSTRAINT FK_9FA3E41419EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE results ADD CONSTRAINT FK_9FA3E41471D7BA9A FOREIGN KEY (medical_staff_id) REFERENCES medical_staff (id)');
        $this->addSql('ALTER TABLE hall ADD CONSTRAINT FK_1B8FA83F48B3EEE4 FOREIGN KEY (departament_id) REFERENCES departament (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495519EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495571D7BA9A FOREIGN KEY (medical_staff_id) REFERENCES medical_staff (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE items_location ADD CONSTRAINT FK_29E53905546DB290 FOREIGN KEY (medical_item_id) REFERENCES medical_item (id)');
        $this->addSql('ALTER TABLE items_location_hall ADD CONSTRAINT FK_E8066DDFBE6E9CBF FOREIGN KEY (items_location_id) REFERENCES items_location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE items_location_hall ADD CONSTRAINT FK_E8066DDF52AFCFD6 FOREIGN KEY (hall_id) REFERENCES hall (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE items_location_room ADD CONSTRAINT FK_8116947BBE6E9CBF FOREIGN KEY (items_location_id) REFERENCES items_location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE items_location_room ADD CONSTRAINT FK_8116947B54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B48B3EEE4 FOREIGN KEY (departament_id) REFERENCES departament (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD248B3EEE4 FOREIGN KEY (departament_id) REFERENCES departament (id)');
        $this->addSql('ALTER TABLE medical_staff ADD CONSTRAINT FK_7788A8C2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE medical_staff ADD CONSTRAINT FK_7788A8C252AFCFD6 FOREIGN KEY (hall_id) REFERENCES hall (id)');
        $this->addSql('ALTER TABLE medical_staff ADD CONSTRAINT FK_7788A8C23B5A08D7 FOREIGN KEY (speciality_id) REFERENCES speciality (id)');
        $this->addSql('ALTER TABLE medical_staff_service ADD CONSTRAINT FK_60030C8671D7BA9A FOREIGN KEY (medical_staff_id) REFERENCES medical_staff (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medical_staff_service ADD CONSTRAINT FK_60030C86ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE anamnesis ADD CONSTRAINT FK_F0A5806919EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE anamnesis ADD CONSTRAINT FK_F0A5806971D7BA9A FOREIGN KEY (medical_staff_id) REFERENCES medical_staff (id)');
        $this->addSql('ALTER TABLE anamnesis ADD CONSTRAINT FK_F0A58069471C0366 FOREIGN KEY (treatment_id) REFERENCES treatment (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE schedule_client DROP FOREIGN KEY FK_A565404AA40BC2D5');
        $this->addSql('ALTER TABLE schedule_medical_staff DROP FOREIGN KEY FK_1F5935A3A40BC2D5');
        $this->addSql('ALTER TABLE items_location_hall DROP FOREIGN KEY FK_E8066DDF52AFCFD6');
        $this->addSql('ALTER TABLE medical_staff DROP FOREIGN KEY FK_7788A8C252AFCFD6');
        $this->addSql('ALTER TABLE hall DROP FOREIGN KEY FK_1B8FA83F48B3EEE4');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B48B3EEE4');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD248B3EEE4');
        $this->addSql('ALTER TABLE items_location DROP FOREIGN KEY FK_29E53905546DB290');
        $this->addSql('ALTER TABLE items_location_hall DROP FOREIGN KEY FK_E8066DDFBE6E9CBF');
        $this->addSql('ALTER TABLE items_location_room DROP FOREIGN KEY FK_8116947BBE6E9CBF');
        $this->addSql('ALTER TABLE medical_staff DROP FOREIGN KEY FK_7788A8C23B5A08D7');
        $this->addSql('ALTER TABLE schedule_client DROP FOREIGN KEY FK_A565404A19EB6921');
        $this->addSql('ALTER TABLE results DROP FOREIGN KEY FK_9FA3E41419EB6921');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495519EB6921');
        $this->addSql('ALTER TABLE anamnesis DROP FOREIGN KEY FK_F0A5806919EB6921');
        $this->addSql('ALTER TABLE anamnesis DROP FOREIGN KEY FK_F0A58069471C0366');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB54177093');
        $this->addSql('ALTER TABLE items_location_room DROP FOREIGN KEY FK_8116947B54177093');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955ED5CA9E6');
        $this->addSql('ALTER TABLE medical_staff_service DROP FOREIGN KEY FK_60030C86ED5CA9E6');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455A76ED395');
        $this->addSql('ALTER TABLE medical_staff DROP FOREIGN KEY FK_7788A8C2A76ED395');
        $this->addSql('ALTER TABLE schedule_medical_staff DROP FOREIGN KEY FK_1F5935A371D7BA9A');
        $this->addSql('ALTER TABLE results DROP FOREIGN KEY FK_9FA3E41471D7BA9A');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495571D7BA9A');
        $this->addSql('ALTER TABLE medical_staff_service DROP FOREIGN KEY FK_60030C8671D7BA9A');
        $this->addSql('ALTER TABLE anamnesis DROP FOREIGN KEY FK_F0A5806971D7BA9A');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE schedule_client');
        $this->addSql('DROP TABLE schedule_medical_staff');
        $this->addSql('DROP TABLE results');
        $this->addSql('DROP TABLE hall');
        $this->addSql('DROP TABLE departament');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE medical_item');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE items_location');
        $this->addSql('DROP TABLE items_location_hall');
        $this->addSql('DROP TABLE items_location_room');
        $this->addSql('DROP TABLE speciality');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE treatment');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE medical_staff');
        $this->addSql('DROP TABLE medical_staff_service');
        $this->addSql('DROP TABLE anamnesis');
    }
}
