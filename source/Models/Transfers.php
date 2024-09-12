<?php
namespace Source\Models;

use Source\Core\Model;

/**
 * Transfers Models
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Models
 */
class Transfers extends Model
{
    /** @var string Uuid */
    protected string $uuid = "uuid";

    /** @var string Id da pessoa que faz a transferência */
    protected string $idFromTransfer = "id_from_transfer";

    /** @var string Id da pessoa que recebe a transferência */
    protected string $idToTransfer = "id_to_transfer";

    /** @var string Valor da transação */
    protected string $transferValue = "transfer_value";

    /** @var string Data da transação */
    protected string $createdAt = "created_at";

    /**
     * Transfers constructor
     */
    public function __construct()
    {
        parent::__construct(CONF_DB_NAME . ".transfers", ["id"], [
            $this->uuid,
            $this->idFromTransfer,
            $this->idToTransfer,
            $this->transferValue
        ]);
    }

    public function setUuid(string $uuid)
    {
        $this->uuid = $uuid;
    }

    public function getUuid()
    {
        return $this->uuid;
    }
}
