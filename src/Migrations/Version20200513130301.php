<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200513130301 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE system (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE refuel ADD system_id INT NOT NULL, ADD date DATE NOT NULL, ADD time TIME NOT NULL, ADD code_card VARCHAR(255) NOT NULL, ADD code_driver VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE refuel ADD CONSTRAINT FK_B60345A1D0952FA5 FOREIGN KEY (system_id) REFERENCES system (id)');
        $this->addSql('CREATE INDEX IDX_B60345A1D0952FA5 ON refuel (system_id)');
        $this->addSql('ALTER TABLE user CHANGE homeagency_id homeagency_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE api_token api_token VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE refuel DROP FOREIGN KEY FK_B60345A1D0952FA5');
        $this->addSql('DROP TABLE system');
        $this->addSql('DROP INDEX IDX_B60345A1D0952FA5 ON refuel');
        $this->addSql('ALTER TABLE refuel DROP system_id, DROP date, DROP time, DROP code_card, DROP code_driver');
        $this->addSql('ALTER TABLE user CHANGE homeagency_id homeagency_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE api_token api_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
