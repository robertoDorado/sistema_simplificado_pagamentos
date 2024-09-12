<?php
namespace Source\Domain\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Source\Domain\Model\User;
use Source\Models\Transfers;
use Source\Models\User as ModelsUser;

/**
 * UserTest Domain\Tests
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Domain\Tests
 */
class UserTest extends TestCase
{
    public function testSetId()
    {
        $user = new User(ModelsUser::class);
        $response = $user->setId(10);
        $this->assertEmpty($response);
    }
    
    public function testEmptyIdMessage()
    {
        $user = new User(ModelsUser::class);
        $user->getId();

        $this->assertJsonStringEqualsJsonString(
            json_encode(["error" => "id inválido"]),
            $user->message->json()
        );
    }
    
    public function testGetId()
    {
        $user = new User(ModelsUser::class);
        $user->setId(10);
        $response = $user->getId();
        $this->assertIsInt($response);
    }
    
    public function testFindUserByIdIsEmpty()
    {
        $user = new User(ModelsUser::class);
        $user->setId(10000000);
        $userData = $user->findUserById(["id"], "payee");
        $this->assertNull($userData);
    }

    public function testInvalidUserType()
    {
        $user = new User(ModelsUser::class);
        $user->setId(10000000);
        $user->findUserById(["id"], "common_user");

        $this->assertJsonStringEqualsJsonString(
            json_encode(["error" => "tipo de usuário inválido"]),
            $user->message->json()
        );
    }

    public function testInvalidTransfer()
    {
        $userModel = new ModelsUser();
        $userData = $userModel->find(
            "user_document=:user_document", 
            ":user_document=09.267.331/0001-67",
            "id"
        )->fetch();

        $user = new User(ModelsUser::class);
        $user->setId($userData->id);
        $userData = $user->findUserById([], "payer");
        $this->assertNull($userData);
        $this->assertJsonStringEqualsJsonString(
            json_encode(["error" => "usuário pagante não pode ser lojista"]),
            $user->message->json()
        );
    }

    public function testFindUserById()
    {
        $userModel = new ModelsUser();
        $userData = $userModel->find(
            "user_document=:user_document", 
            ":user_document=09.267.331/0001-67",
            "id"
        )->fetch();

        $user = new User(ModelsUser::class);
        $user->setId($userData->id);
        $userData = $user->findUserById([], "payee");
        $this->assertInstanceOf(ModelsUser::class, $userData);
    }
}
