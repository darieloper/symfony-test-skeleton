<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migration\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210525011255 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Tag table migration';
    }

    public function up(Schema $schema) : void
    {
        $this->checkEngine();
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $this->checkEngine();
        $this->addSql('DROP TABLE tag');
    }
}
