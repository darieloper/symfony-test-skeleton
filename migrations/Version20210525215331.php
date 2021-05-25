<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migration\BaseMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210525215331 extends BaseMigration
{
    public function getDescription() : string
    {
        return 'Product table migration';
    }

    public function up(Schema $schema) : void
    {
        $this->checkEngine();
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(125) NOT NULL, price DOUBLE PRECISION NOT NULL, description TEXT DEFAULT NULL, stock INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        $this->checkEngine();
        $this->addSql('DROP TABLE product');
    }
}
