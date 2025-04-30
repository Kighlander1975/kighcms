<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430194610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE user_settings_roles (user_id INT NOT NULL, settings_id INT NOT NULL, INDEX IDX_6562B7D7A76ED395 (user_id), INDEX IDX_6562B7D759949888 (settings_id), PRIMARY KEY(user_id, settings_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_settings_roles ADD CONSTRAINT FK_6562B7D7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_settings_roles ADD CONSTRAINT FK_6562B7D759949888 FOREIGN KEY (settings_id) REFERENCES settings (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user_settings_roles DROP FOREIGN KEY FK_6562B7D7A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_settings_roles DROP FOREIGN KEY FK_6562B7D759949888
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_settings_roles
        SQL);
    }
}
