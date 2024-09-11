<?php
namespace Source\Migrations;

require __DIR__ . "/../../vendor/autoload.php";

use Source\Migrations\Core\DDL;
use Source\Models\TestModel as ModelsTestModel;

/**
 * TestModel Migrations
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Migrations
 */
class TestModel
{
    /** @var DDL Data Definition Language */
    private DDL $ddl;
    /**
     * TestModel constructor
     */
    public function __construct()
    {
        $this->ddl = new DDL(ModelsTestModel::class);
    }

    /**
     * Modificação da coluna D para 1000 caracteres no dia 2023-11-05
     *
     * @return void
     */
    public function modifyVarcharColumnD()
    {
        $this->ddl->alterTable(["MODIFY COLUMN column_d VARCHAR(1000) NOT NULL"]);
        // return $this->ddl->getQuery(); # Debug da Query DDL
        $this->ddl->executeQuery();
    }

    /**
     * Tabela criada no dia 2023-11-04
     *
     * @return void
     */
    public function defineTable()
    {
        $this->ddl->setClassProperties();
        $this->ddl->setKeysToProperties(["BIGINT AUTO_INCREMENT PRIMARY KEY", "VARCHAR(255) NOT NULL",
        "VARCHAR(255) NOT NULL", "VARCHAR(255) NOT NULL", "VARCHAR(255) NOT NULL"]);
        $this->ddl->dropTableIfExists()->createTableQuery();
        // return $this->ddl->getQuery(); # Debug da Query DDL
        $this->ddl->executeQuery();
    }
}
executeMigrations(TestModel::class);