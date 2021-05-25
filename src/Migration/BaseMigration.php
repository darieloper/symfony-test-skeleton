<?php
declare(strict_types=1);

namespace App\Migration;

use Doctrine\Migrations\AbstractMigration;

abstract class BaseMigration extends AbstractMigration {
    protected $dbEngine = 'mysql';
    protected $errorMessage = 'Migration can only be executed safely on \'%s\'.';

    protected function isWrongEngine() {
        return $this->connection->getDatabasePlatform()->getName() !== $this->dbEngine;
    }

    protected function checkEngine() {
        $this->abortIf($this->isWrongEngine(), sprintf($this->errorMessage, $this->dbEngine));
    }
}