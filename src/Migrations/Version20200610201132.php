<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200610201132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE items_location_hall DROP FOREIGN KEY FK_E8066DDFBE6E9CBF');
        $this->addSql('ALTER TABLE items_location_room DROP FOREIGN KEY FK_8116947BBE6E9CBF');
        $this->addSql('ALTER TABLE items_location DROP FOREIGN KEY FK_29E53905546DB290');
        $this->addSql('DROP TABLE items_location');
        $this->addSql('DROP TABLE items_location_hall');
        $this->addSql('DROP TABLE items_location_room');
        $this->addSql('DROP TABLE medical_item');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE items_location (id INT AUTO_INCREMENT NOT NULL, medical_item_id INT NOT NULL, INDEX IDX_29E53905546DB290 (medical_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE items_location_hall (items_location_id INT NOT NULL, hall_id INT NOT NULL, INDEX IDX_E8066DDFBE6E9CBF (items_location_id), INDEX IDX_E8066DDF52AFCFD6 (hall_id), PRIMARY KEY(items_location_id, hall_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE items_location_room (items_location_id INT NOT NULL, room_id INT NOT NULL, INDEX IDX_8116947BBE6E9CBF (items_location_id), INDEX IDX_8116947B54177093 (room_id), PRIMARY KEY(items_location_id, room_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE medical_item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, status TINYINT(1) NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE items_location ADD CONSTRAINT FK_29E53905546DB290 FOREIGN KEY (medical_item_id) REFERENCES medical_item (id)');
        $this->addSql('ALTER TABLE items_location_hall ADD CONSTRAINT FK_E8066DDF52AFCFD6 FOREIGN KEY (hall_id) REFERENCES hall (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE items_location_hall ADD CONSTRAINT FK_E8066DDFBE6E9CBF FOREIGN KEY (items_location_id) REFERENCES items_location (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE items_location_room ADD CONSTRAINT FK_8116947B54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE items_location_room ADD CONSTRAINT FK_8116947BBE6E9CBF FOREIGN KEY (items_location_id) REFERENCES items_location (id) ON DELETE CASCADE');
    }
}
