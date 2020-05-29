<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200529063219 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE refuel ADD creator_user_id INT DEFAULT NULL, ADD modifier_user_id INT DEFAULT NULL, ADD creation_date DATETIME NOT NULL, ADD modification_date DATETIME NOT NULL, CHANGE code_driver code_driver VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE refuel ADD CONSTRAINT FK_B60345A129FC6AE1 FOREIGN KEY (creator_user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE refuel ADD CONSTRAINT FK_B60345A165787AC2 FOREIGN KEY (modifier_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B60345A129FC6AE1 ON refuel (creator_user_id)');
        $this->addSql('CREATE INDEX IDX_B60345A165787AC2 ON refuel (modifier_user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496973EC66');
        $this->addSql('DROP INDEX UNIQ_8D93D6496973EC66 ON user');
        $this->addSql('ALTER TABLE user DROP refreshToken, CHANGE homeagency_id homeagency_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE api_token api_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE refresh_token CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE refresh_token CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE refuel DROP FOREIGN KEY FK_B60345A129FC6AE1');
        $this->addSql('ALTER TABLE refuel DROP FOREIGN KEY FK_B60345A165787AC2');
        $this->addSql('DROP INDEX IDX_B60345A129FC6AE1 ON refuel');
        $this->addSql('DROP INDEX IDX_B60345A165787AC2 ON refuel');
        $this->addSql('ALTER TABLE refuel DROP creator_user_id, DROP modifier_user_id, DROP creation_date, DROP modification_date, CHANGE code_driver code_driver VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user ADD refreshToken INT DEFAULT NULL, CHANGE homeagency_id homeagency_id INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE api_token api_token VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496973EC66 FOREIGN KEY (refreshToken) REFERENCES refresh_token (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496973EC66 ON user (refreshToken)');
    }
}
