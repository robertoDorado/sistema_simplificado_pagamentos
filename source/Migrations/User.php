<?php
namespace Source\Migrations;

use Exception;
use Ramsey\Uuid\Nonstandard\Uuid;
use Source\Migrations\Core\DDL;
use Source\Models\User as ModelsUser;

require dirname(dirname(__DIR__)) . "/vendor/autoload.php";

/**
 * User Migrations
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Migrations
 */
class User extends DDL
{
    /**
     * User constructor
     */
    public function __construct()
    {
        parent::__construct(ModelsUser::class);
    }

    public function defineTable()
    {
        $this->setClassProperties();
        $this->setKeysToProperties([
            "BIGINT AUTO_INCREMENT PRIMARY KEY",
            "VARCHAR(36) UNIQUE NOT NULL",
            "VARCHAR(255) NOT NULL",
            "VARCHAR(255) UNIQUE NOT NULL",
            "VARCHAR(255) UNIQUE NOT NULL",
            "VARCHAR(255) NOT NULL",
            "DECIMAL(10,2) NOT NULL",
            "TINYINT(1) NOT NULL"
        ]);

        $response = $this->setForeignKeyChecks(0)->dropTableIfExists()->createTableQuery()->setForeignKeyChecks(1);
        $response->executeQuery();

        $faker = \Faker\Factory::create();
        $user = new ModelsUser();
        $user->uuid = Uuid::uuid4();
        $user->full_name = $faker->name();
        $user->user_document = "09.267.331/0001-67";
        $user->user_email = $faker->email();
        $user->user_password = password_hash($faker->password(), PASSWORD_DEFAULT);
        $balance = mt_rand(1, 10000) / 100;
        $user->user_balance = number_format($balance, 2, '.', '');
        $user->user_type = 1;
        $user->save();

        $user = new ModelsUser();
        $user->uuid = Uuid::uuid4();
        $user->full_name = $faker->name();
        $user->user_document = "074.954.210-10";
        $user->user_email = $faker->email();
        $user->user_password = password_hash($faker->password(), PASSWORD_DEFAULT);
        $balance = mt_rand(1, 10000) / 100;
        $user->user_balance = number_format($balance, 2, '.', '');
        $user->user_type = 0;
        $user->save();

        $user = new ModelsUser();
        $user->uuid = Uuid::uuid4();
        $user->full_name = $faker->name();
        $user->user_document = "625.302.910-04";
        $user->user_email = $faker->email();
        $user->user_password = password_hash($faker->password(), PASSWORD_DEFAULT);
        $balance = mt_rand(1, 10000) / 100;
        $user->user_balance = number_format($balance, 2, '.', '');
        $user->user_type = 0;
        $user->save();
    }
}
executeMigrations(User::class);