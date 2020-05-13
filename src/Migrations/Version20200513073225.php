<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200513073225 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE truck DROP FOREIGN KEY FK_CDCCF30A81C06096');
        $this->addSql('ALTER TABLE refuel DROP FOREIGN KEY FK_B60345A1C3423909');
        $this->addSql('ALTER TABLE code DROP FOREIGN KEY FK_77153098D0952FA5');
        $this->addSql('ALTER TABLE integrationmodel DROP FOREIGN KEY FK_BF2E3FD9D0952FA5');
        $this->addSql('ALTER TABLE code DROP FOREIGN KEY FK_77153098C6957CCE');
        $this->addSql('ALTER TABLE refuel DROP FOREIGN KEY FK_B60345A1C6957CCE');
        $this->addSql('ALTER TABLE truck DROP FOREIGN KEY FK_CDCCF30AC54C8C93');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE code');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE integrationmodel');
        $this->addSql('DROP TABLE system');
        $this->addSql('DROP TABLE truck');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP INDEX IDX_B60345A1C6957CCE ON refuel');
        $this->addSql('DROP INDEX IDX_B60345A1C3423909 ON refuel');
        $this->addSql('ALTER TABLE refuel DROP driver_id, DROP truck_id');
        $this->addSql('ALTER TABLE user CHANGE homeagency_id homeagency_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE api_token api_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE code (id INT AUTO_INCREMENT NOT NULL, truck_id INT NOT NULL, system_id INT NOT NULL, code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_77153098D0952FA5 (system_id), INDEX IDX_77153098C6957CCE (truck_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE driver (id INT AUTO_INCREMENT NOT NULL, homeagency_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, firstname VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_11667CD9862F6747 (homeagency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE integrationmodel (id INT AUTO_INCREMENT NOT NULL, system_id INT NOT NULL, homeagency_id INT NOT NULL, volumelocation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, datelocation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, dateformat VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, codetrucklocation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, codedriverlocation VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, mileagetrucklocation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_BF2E3FD9862F6747 (homeagency_id), INDEX IDX_BF2E3FD9D0952FA5 (system_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE system (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE truck (id INT AUTO_INCREMENT NOT NULL, homeagency_id INT NOT NULL, activity_id INT NOT NULL, type_id INT NOT NULL, numberplate VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_CDCCF30A81C06096 (activity_id), INDEX IDX_CDCCF30AC54C8C93 (type_id), INDEX IDX_CDCCF30A862F6747 (homeagency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT FK_77153098C6957CCE FOREIGN KEY (truck_id) REFERENCES truck (id)');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT FK_77153098D0952FA5 FOREIGN KEY (system_id) REFERENCES system (id)');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9862F6747 FOREIGN KEY (homeagency_id) REFERENCES homeagency (id)');
        $this->addSql('ALTER TABLE integrationmodel ADD CONSTRAINT FK_BF2E3FD9862F6747 FOREIGN KEY (homeagency_id) REFERENCES homeagency (id)');
        $this->addSql('ALTER TABLE integrationmodel ADD CONSTRAINT FK_BF2E3FD9D0952FA5 FOREIGN KEY (system_id) REFERENCES system (id)');
        $this->addSql('ALTER TABLE truck ADD CONSTRAINT FK_CDCCF30A81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE truck ADD CONSTRAINT FK_CDCCF30A862F6747 FOREIGN KEY (homeagency_id) REFERENCES homeagency (id)');
        $this->addSql('ALTER TABLE truck ADD CONSTRAINT FK_CDCCF30AC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE refuel ADD driver_id INT DEFAULT NULL, ADD truck_id INT NOT NULL');
        $this->addSql('ALTER TABLE refuel ADD CONSTRAINT FK_B60345A1C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE refuel ADD CONSTRAINT FK_B60345A1C6957CCE FOREIGN KEY (truck_id) REFERENCES truck (id)');
        $this->addSql('CREATE INDEX IDX_B60345A1C6957CCE ON refuel (truck_id)');
        $this->addSql('CREATE INDEX IDX_B60345A1C3423909 ON refuel (driver_id)');
        $this->addSql('ALTER TABLE user CHANGE homeagency_id homeagency_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE api_token api_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
