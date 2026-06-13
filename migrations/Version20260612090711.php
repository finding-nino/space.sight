<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260612090711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apod_entry (id INT AUTO_INCREMENT NOT NULL, date VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, hdurl VARCHAR(255) DEFAULT NULL, media_type VARCHAR(255) NOT NULL, explanation LONGTEXT NOT NULL, copyright VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4BE5CE43AA9E377A (date), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE apod_entry');
    }
}
