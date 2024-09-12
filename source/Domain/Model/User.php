<?php
namespace Source\Domain\Model;

use Exception;
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
    private Message $message;

    /** @var int */
    private int $id;

    /**
     * User constructor
     */
    public function __construct(string $class)
    {
        $this->user = new $class();
        if (!$this->user instanceof ModelsUser) {
            throw new Exception("a classe " . $class . " é inválida");
        }
        $this->message = new Message();
    }

    public function getId()
    {
        $this->message->error("id inválido");
        if (empty($this->id)) {
            http_response_code(500);
            echo $this->message->json();
            die;
        }

        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function findUserById(array $columns): ?ModelsUser
    {
        $columns = empty($columns) ? "*" : implode(", ", $columns);
        return $this->user->findById($this->getId());
    }
}
