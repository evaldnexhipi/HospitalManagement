<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200425172747 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE medical_staff_service (medical_staff_id INT NOT NULL, service_id INT NOT NULL, INDEX IDX_60030C8671D7BA9A (medical_staff_id), INDEX IDX_60030C86ED5CA9E6 (service_id), PRIMARY KEY(medical_staff_id, service_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medical_staff_service ADD CONSTRAINT FK_60030C8671D7BA9A FOREIGN KEY (medical_staff_id) REFERENCES medical_staff (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE medical_staff_service ADD CONSTRAINT FK_60030C86ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE medical_staff_service');
    }
}
