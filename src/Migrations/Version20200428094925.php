<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20200428094925
 * @package DoctrineMigrations
 * @author bernard-ng <ngandubernard@gmail.com>
 */
final class Version20200428094925 extends AbstractMigration
{
    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getDescription() : string
    {
        return 'Create User table => Stores Users Data and Authenticates them';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, uid VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, avatar_url VARCHAR(255) DEFAULT \'/images/system/default_avatar.jpg\', account_confirmation_token VARCHAR(70) DEFAULT NULL, account_confirmed_at DATETIME DEFAULT NULL, password_reset_token VARCHAR(70) DEFAULT NULL, password_reset_at DATETIME DEFAULT NULL, is_private TINYINT(1) DEFAULT \'0\' NOT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, is_certified TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649539B0606 (uid), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6495E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user');
    }
}
