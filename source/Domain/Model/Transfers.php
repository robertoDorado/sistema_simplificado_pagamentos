<?php
namespace Source\Domain\Model;

use Source\Core\Connect;
use Source\Models\Transfers as ModelsTransfers;
use Source\Support\Message;

/**
 * Transfers Domain\Model
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Domain\Model
 */
class Transfers
{
    /** @var Message */
    public Message $message;

    /** @var ModelsTransfers */
    public ModelsTransfers $transfers;

    /** @var int */
    public int $id = 0;

    /**
     * Transfers constructor
     */
    public function __construct(string $class)
    {
        $this->message = new Message();
        $this->transfers = new $class();
    }

    public function persistData(array $data): bool
    {
        foreach ($data as $key => $value) {
            $this->transfers->$key = $value;
        }
        $this->transfers->save();
        $this->id = Connect::getInstance()->lastInsertId();
        return true;
    }
}
