<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200417083759 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE homeagency_integrationmodel');
        $this->addSql('ALTER TABLE integrationmodel ADD homeagency_id INT NOT NULL, CHANGE codedriverlocation codedriverlocation VARCHAR(255) DEFAULT NULL, CHANGE dateformatlocation dateformat VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE integrationmodel ADD CONSTRAINT FK_BF2E3FD9862F6747 FOREIGN KEY (homeagency_id) REFERENCES homeagency (id)');
        $this->addSql('CREATE INDEX IDX_BF2E3FD9862F6747 ON integrationmodel (homeagency_id)');
        $this->addSql('ALTER TABLE refuel CHANGE driver_id driver_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE homeagency_id homeagency_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE homeagency_integrationmodel (homeagency_id INT NOT NULL, integrationmodel_id INT NOT NULL, INDEX IDX_C5394F0862F6747 (homeagency_id), INDEX IDX_C5394F06011ECC3 (integrationmodel_id), PRIMARY KEY(homeagency_id, integrationmodel_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE homeagency_integrationmodel ADD CONSTRAINT FK_C5394F06011ECC3 FOREIGN KEY (integrationmodel_id) REFERENCES integrationmodel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE homeagency_integrationmodel ADD CONSTRAINT FK_C5394F0862F6747 FOREIGN KEY (homeagency_id) REFERENCES homeagency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE integrationmodel DROP FOREIGN KEY FK_BF2E3FD9862F6747');
        $this->addSql('DROP INDEX IDX_BF2E3FD9862F6747 ON integrationmodel');
        $this->addSql('ALTER TABLE integrationmodel DROP homeagency_id, CHANGE codedriverlocation codedriverlocation VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE dateformat dateformatlocation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE refuel CHANGE driver_id driver_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE homeagency_id homeagency_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
