<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migration\BaseMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210525212640 extends BaseMigration
{
    public function getDescription() : string
    {
        return 'Image table migration';
    }

    public function up(Schema $schema) : void
    {
        $this->checkEngine();
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, title VARCHAR(125) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $this->checkEngine();
        $this->addSql('DROP TABLE image');
    }
}
