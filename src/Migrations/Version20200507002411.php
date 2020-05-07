<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20200507002411
 * @package DoctrineMigrations
 * @author bernard-ng <ngandubernard@gmail.com>
 */
final class Version20200507002411 extends AbstractMigration
{
    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function up(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE password_reset_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, expiry_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_6B7BA4B6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE password_reset_token ADD CONSTRAINT FK_6B7BA4B6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user DROP password_reset_token, DROP password_reset_at');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function down(Schema $schema): void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE password_reset_token');
        $this->addSql('ALTER TABLE user ADD password_reset_token VARCHAR(70) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, ADD password_reset_at DATETIME DEFAULT \'NULL\'');
    }
}
