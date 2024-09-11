<?php

namespace Source\Support;

use Exception;
use Source\Core\Session;

/**
 * RequestPost Source\Support
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Support
 */
class Requests
{
    /** @var array Aramazena a variavel global $_POST */
    protected array $post;

    /** @var array Armazena a variavel global $_GET */
    protected array $get;

    /** @var Session */
    protected $session;

    /** @var string Campo senha */
    protected string $password;

    /** @var string Campo confirme a senha */
    protected string $confirmPassword;

    /** @var bool Atributo para controlar atribuição da hash em campo do tipo senha */
    protected bool $hashPassword = true;

    /** @var bool Atributo para validar a existência da tag csrfToken */
    protected bool $csrfTokenExists = false;

    /**
     * RequestPost constructor
     */
    public function __construct(Session $session)
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->session = !empty($session) ? $session : null;
    }

    public function get($key, $default = null)
    {
        return isset($this->get[$key]) ? $this->get[$key] : $default;
    }

    public function has($key): bool
    {
        return isset($this->get[$key]);
    }
    
    public function configureDataPost()
    {
        array_walk($this->post, [$this, 'formConfigure']);
        if (!$this->csrfTokenExists) {
            throw new \Exception("Token csrf não existe");
        }
        return $this;
    }

    private function verifyData(string $field, string $key, $value) {
        if ($field == $key) {
            if (!isset($value)) {
                throw new \Exception("Campo " . $key . " é obrigatório.");
            }
        }
    }

    public function setHashPassword(bool $bool)
    {
        $this->hashPassword = $bool;
        return $this;
    }

    private function getHashPassword()
    {
        return $this->hashPassword;
    }

    public function setRequiredFields(array $requiredFields)
    {
        foreach ($requiredFields as $value) {
            if (!in_array($value, array_keys($this->post))) {
                throw new Exception("O campo " . $value . " é obrigatório");
            }
        }

        $verifyRequiredFields = function (&$value, $key) use ($requiredFields) {
            if (!empty($requiredFields)) {
                foreach ($requiredFields as $field) {
                    $this->verifyData($field, $key, $value);
                }
            }
        };

        array_walk($this->post, $verifyRequiredFields);
        return $this;
    }

    private function formConfigure(&$value, $key)
    {
        if ($key == "password") {
            $this->password = $value;
        }

        if ($key == "confirmPassword") {
            $this->confirmPassword = $value;
        }

        if (!empty($this->password) && !empty($this->confirmPassword)) {
            if ($this->password != $this->confirmPassword) {
                echo json_encode(["invalid_passwords_value" => true,
                "msg" => "Campo senha e confirme a sua senha são diferentes"]);
                die;
            }
        }
        
        if ($key == "csrfToken" || $key == "csrf_token") {
            $this->csrfTokenExists = true;
            if ($value != $this->session->csrf_token) {
                throw new \Exception("Token csrf inválido");
            }
        }

        if ($key == "email" || $key == "confirmEmail") {
            if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $value)) {
                throw new \Exception("Endereço de e-mail inválido");
            }
        }

        if ($key == "userName") {
            $userName = explode(" ", $value);
            $value = count($userName) > 0 ? implode("", $userName) : $value;
        }

        if ($this->getHashPassword()) {
            if ($key === 'password' || $key === 'confirmPassword') {
                $value = password_hash($value, PASSWORD_DEFAULT);
            }
        }
    }

    public function getPost(string $key, $default = null)
    {
        return isset($this->post[$key]) ? $this->post[$key] : $default;
    }

    public function getAllPostData()
    {
        return $this->post;
    }
}
