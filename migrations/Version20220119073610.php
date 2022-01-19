<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220119073610 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE api_token (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(255) NOT NULL, expire_at DATETIME NOT NULL, client_ip VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7BA2F5EB5F37A13B (token), UNIQUE INDEX UNIQ_7BA2F5EB331BD5C (client_ip), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BFCC72851DBE09B ON router_details (sapid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BFCC728E551C011 ON router_details (hostname)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BFCC728D36215FA ON router_details (loopback)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BFCC728B728E969 ON router_details (mac_address)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE api_token');
        $this->addSql('DROP INDEX UNIQ_8BFCC72851DBE09B ON router_details');
        $this->addSql('DROP INDEX UNIQ_8BFCC728E551C011 ON router_details');
        $this->addSql('DROP INDEX UNIQ_8BFCC728D36215FA ON router_details');
        $this->addSql('DROP INDEX UNIQ_8BFCC728B728E969 ON router_details');
    }
}
