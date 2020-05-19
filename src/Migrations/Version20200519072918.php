<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200519072918 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE refuel ADD product_id INT NOT NULL, DROP type_produit, CHANGE code_driver code_driver VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE refuel ADD CONSTRAINT FK_B60345A14584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_B60345A14584665A ON refuel (product_id)');
        $this->addSql('ALTER TABLE system ADD diesel_file_label VARCHAR(255) NOT NULL, ADD adblue_fiel_label VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE homeagency_id homeagency_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE api_token api_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE refuel DROP FOREIGN KEY FK_B60345A14584665A');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP INDEX IDX_B60345A14584665A ON refuel');
        $this->addSql('ALTER TABLE refuel ADD type_produit VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP product_id, CHANGE code_driver code_driver VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE system DROP diesel_file_label, DROP adblue_fiel_label');
        $this->addSql('ALTER TABLE user CHANGE homeagency_id homeagency_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE api_token api_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
