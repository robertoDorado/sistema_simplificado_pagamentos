<?php
namespace Source\Domain\Model;

use Source\Models\User as ModelsUser;
use Source\Support\Message;

/**
 * User Domain\Model
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Domain\Model
 */
class User
{
    /** @var ModelsUser */
    private ModelsUser $user;

    /** @var Message */
    public Message $message;

    /** @var int */
    private int $id;

    /**
     * User constructor
     */
    public function __construct(string $class)
    {
        $this->user = new $class();
        $this->message = new Message();
    }

    public function getId(): int
    {
        $this->message->error("id inválido");
        if (empty($this->id)) {
            http_response_code(400);
            $this->message->json();
            return 0;
        }

        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function findUserById(array $columns, string $userType): ?ModelsUser
    {
        $validateUserType = ["payer", "payee"];
        if (!in_array($userType, $validateUserType)) {
            $this->message->error("tipo de usuário inválido");
            return null;
        }

        $columns = empty($columns) ? "*" : implode(", ", $columns);
        $response = $this->user->findById($this->getId());

        if (empty($response)) {
            $this->message->error("{$userType} não encontrado");
            return null;
        }

        if ($userType == "payer") {
            if (!empty($response->user_type)) {
                $this->message->error("usuário pagante não pode ser lojista");
                return null;
            }
        }

        return $response;
    }
}
