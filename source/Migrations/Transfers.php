<?php
namespace Source\Migrations;

use Exception;
use Source\Migrations\Core\DDL;
use Source\Models\Transfers as ModelsTransfers;

require dirname(dirname(__DIR__)) . "/vendor/autoload.php";

/**
 * Transfers Migrations
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Migrations
 */
class Transfers extends DDL
{
    /**
     * Transfers constructor
     */
    public function __construct()
    {
        parent::__construct(ModelsTransfers::class);
    }

    public function defineTable()
    {
        $this->setClassProperties();
        $this->setProperty('');
        $this->setProperty('');
        $this->setKeysToProperties([
            "BIGINT AUTO_INCREMENT PRIMARY KEY",
            "VARCHAR(36) UNIQUE NOT NULL",
            "BIGINT NOT NULL",
            "BIGINT NOT NULL",
            "DECIMAL(10,2) NOT NULL",
            "DATE NOT NULL",
            "CONSTRAINT fk_from_user FOREIGN KEY (id_from_transfer) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE",
            "CONSTRAINT fk_to_user FOREIGN KEY (id_to_transfer) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE",
        ]);

        $response = $this->setForeignKeyChecks(0)->dropTableIfExists()->createTableQuery()->setForeignKeyChecks(1);
        $response->executeQuery();
    }
}
executeMigrations(Transfers::class);