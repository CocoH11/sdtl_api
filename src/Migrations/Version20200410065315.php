<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200410065315 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE driver (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE code (id INT AUTO_INCREMENT NOT NULL, truck_id INT NOT NULL, system_id INT NOT NULL, code VARCHAR(255) NOT NULL, INDEX IDX_77153098C6957CCE (truck_id), INDEX IDX_77153098D0952FA5 (system_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE homeagency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE homeagency_integrationmodel (homeagency_id INT NOT NULL, integrationmodel_id INT NOT NULL, INDEX IDX_C5394F0862F6747 (homeagency_id), INDEX IDX_C5394F06011ECC3 (integrationmodel_id), PRIMARY KEY(homeagency_id, integrationmodel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refuel (id INT AUTO_INCREMENT NOT NULL, driver_id INT DEFAULT NULL, truck_id INT NOT NULL, volume INT NOT NULL, INDEX IDX_B60345A1C3423909 (driver_id), INDEX IDX_B60345A1C6957CCE (truck_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE truck (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, homeagency_id INT NOT NULL, activity_id INT NOT NULL, numberplate VARCHAR(255) NOT NULL, INDEX IDX_CDCCF30AC54C8C93 (type_id), INDEX IDX_CDCCF30A862F6747 (homeagency_id), INDEX IDX_CDCCF30A81C06096 (activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE system (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT FK_77153098C6957CCE FOREIGN KEY (truck_id) REFERENCES truck (id)');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT FK_77153098D0952FA5 FOREIGN KEY (system_id) REFERENCES system (id)');
        $this->addSql('ALTER TABLE homeagency_integrationmodel ADD CONSTRAINT FK_C5394F0862F6747 FOREIGN KEY (homeagency_id) REFERENCES homeagency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE homeagency_integrationmodel ADD CONSTRAINT FK_C5394F06011ECC3 FOREIGN KEY (integrationmodel_id) REFERENCES integrationmodel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE refuel ADD CONSTRAINT FK_B60345A1C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE refuel ADD CONSTRAINT FK_B60345A1C6957CCE FOREIGN KEY (truck_id) REFERENCES truck (id)');
        $this->addSql('ALTER TABLE truck ADD CONSTRAINT FK_CDCCF30AC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE truck ADD CONSTRAINT FK_CDCCF30A862F6747 FOREIGN KEY (homeagency_id) REFERENCES homeagency (id)');
        $this->addSql('ALTER TABLE truck ADD CONSTRAINT FK_CDCCF30A81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('ALTER TABLE integrationmodel ADD CONSTRAINT FK_BF2E3FD9D0952FA5 FOREIGN KEY (system_id) REFERENCES system (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE refuel DROP FOREIGN KEY FK_B60345A1C3423909');
        $this->addSql('ALTER TABLE homeagency_integrationmodel DROP FOREIGN KEY FK_C5394F0862F6747');
        $this->addSql('ALTER TABLE truck DROP FOREIGN KEY FK_CDCCF30A862F6747');
        $this->addSql('ALTER TABLE truck DROP FOREIGN KEY FK_CDCCF30AC54C8C93');
        $this->addSql('ALTER TABLE truck DROP FOREIGN KEY FK_CDCCF30A81C06096');
        $this->addSql('ALTER TABLE code DROP FOREIGN KEY FK_77153098C6957CCE');
        $this->addSql('ALTER TABLE refuel DROP FOREIGN KEY FK_B60345A1C6957CCE');
        $this->addSql('ALTER TABLE integrationmodel DROP FOREIGN KEY FK_BF2E3FD9D0952FA5');
        $this->addSql('ALTER TABLE code DROP FOREIGN KEY FK_77153098D0952FA5');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE code');
        $this->addSql('DROP TABLE homeagency');
        $this->addSql('DROP TABLE homeagency_integrationmodel');
        $this->addSql('DROP TABLE refuel');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE truck');
        $this->addSql('DROP TABLE system');
    }
}
