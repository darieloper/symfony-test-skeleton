<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migration\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210526005327 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add many to many relation between Tag and Product';
    }

    public function up(Schema $schema) : void
    {
        $this->checkEngine();
        $this->addSql('CREATE TABLE product_tag (product_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_E3A6E39C4584665A (product_id), INDEX IDX_E3A6E39CBAD26311 (tag_id), PRIMARY KEY(product_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39C4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_tag ADD CONSTRAINT FK_E3A6E39CBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADBAD26311');
        $this->addSql('DROP INDEX IDX_D34A04ADBAD26311 ON product');
        $this->addSql('ALTER TABLE product DROP tag_id');
    }

    public function down(Schema $schema) : void
    {
        $this->checkEngine();
        $this->addSql('DROP TABLE product_tag');
        $this->addSql('ALTER TABLE product ADD tag_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADBAD26311 ON product (tag_id)');
    }
}
