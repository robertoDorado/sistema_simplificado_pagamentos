<?php
namespace Source\Models;

use Source\Core\Model;

/**
 * User Models
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Models
 */
class User extends Model
{
    /** @var string Uuid */
    protected string $uuid = "uuid";

    /** @var string Nome completo */
    protected string $fullName = "full_name";

    /** @var string  CPF/CNPJ */
    protected string $userDocument = "user_document";

    /** @var string E-mail do usuário */
    protected string $userEmail = "user_email";

    /** @var string Senha de acesso */
    protected string $userPassword = "user_password";

    /** @var string Saldo Final */
    protected string $userBalance = "user_balance";

    /** @var string Tipo de usuário */
    protected string $userType = "user_type";

    /**
     * User constructor
     */
    public function __construct()
    {
        parent::__construct(CONF_DB_NAME . ".user", ["id"], [
            $this->fullName,
            $this->userDocument,
            $this->userEmail,
            $this->userPassword,
            $this->userBalance,
            $this->userType,
            $this->uuid
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
