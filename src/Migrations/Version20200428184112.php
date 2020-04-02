<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


/**
 * Class Version20200428184112
 * @package DoctrineMigrations
 * @author bernard-ng <ngandubernard@gmail.com>
 */
final class Version20200428184112 extends AbstractMigration
{
    /**
     * @return string
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function getDescription() : string
    {
        return 'Create Login Table => Stores Users Login Activity';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE login (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, device VARCHAR(255) NOT NULL, ip VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, logged_at DATETIME NOT NULL, INDEX IDX_AA08CB10A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE login ADD CONSTRAINT FK_AA08CB10A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     * @author bernard-ng <ngandubernard@gmail.com>
     */
    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE login');
    }
}
