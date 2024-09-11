<?php
namespace Source\Models;

use Source\Core\Model;

/**
 * RetailUser Models
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Models
 */
class RetailUser extends Model
{
    /** @var string Nome completo */
    protected string $nameComplete = "name_complete";

    /** @var string  CPF/CNPJ */
    protected string $userDocument = "user_document";

    /** @var string E-mail do usuário */
    protected string $userEmail = "user_email";

    /** @var string Senha de acesso */
    protected string $userPassword = "user_password";

    /**
     * RetailUser constructor
     */
    public function __construct()
    {
        parent::__construct(CONF_DB_NAME . ".retail_user", ["id"], [
            $this->nameComplete,
            $this->userDocument,
            $this->userEmail,
            $this->userPassword
        ]);
    }
}
