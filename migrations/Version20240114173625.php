<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240114173625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id_game INT AUTO_INCREMENT NOT NULL, score INT NOT NULL, level INT NOT NULL, duration INT NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, id_user INT DEFAULT NULL, INDEX IDX_232B318C8D93D649 (id_user), PRIMARY KEY(id_game)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C8D93D649 FOREIGN KEY (id_user) REFERENCES users (id_user)');
    }
    
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C8D93D649');
        $this->addSql('DROP TABLE game');
    }

}
