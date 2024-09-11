<?php

namespace Source\Support;

use Source\Core\Session;

/**
 * Message Support
 * @package Source\Support
 */
class Message
{
    private $text;
    private $type;
    private $icon;

    /**
     * Executado automaticamente quando é dado um echo no objeto
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * getText: recupera o texto da mensagem
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * getType: recupera a classe que será atribuida a mensagem
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    
    /**
     * @param string $message
     * @return Message
     */
    public function info(string $message): Message
    {
        $this->type = "info bg-info"; // border-info text-info";
        $this->icon = "fa-comment-dots";
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * @param string $message
     * @return Message
     */
    public function success(string $message): Message
    {
        $this->type = "success bg-success"; // border-success text-success";
        $this->icon = "fa-check";
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * @param string $message
     * @return Message
     */
    public function warning(string $message): Message
    {
        $this->type = "warning bg-warning"; // border-warning text-warning";
        $this->icon = "fa-exclamation-triangle";
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * @param string $message
     * @return Message
     */
    public function error(string $message): Message
    {
        $this->type = "error bg-danger"; // border-danger text-danger";
        $this->icon = "fa-ban";
        $this->text = $this->filter($message);
        return $this;
    }

    /**
     * render: retorna a mensagem para o controller
     * @return string
     */
    public function render(): string
    {
        return "
            <div class='message {$this->getType()}'>
                <i class='fas {$this->icon} mr-2'></i>
                {$this->getText()}
                <span class='close'>x</span>
            </div>";
    }

    /**
     * @return void
     */
    public function json(bool $assoc = true): string
    {
        $verifyKeyTypeJsonMessage = [
            "info bg-info" => "info",
            "success bg-success" => "success",
            "warning bg-warning" => "warning",
            "error bg-danger" => "error"
        ];

        $key = $verifyKeyTypeJsonMessage[$this->type];
        return $assoc ? json_encode([$key => $this->getText()]) : json_encode([$key, $this->getText()]);
    }

    /**
     * flash: quando enviar um formulario e tiver redirecionando atribuimos a mensagem a uma sessão e apagamos
     * @return void
     */
    public function flash(): void
    {
        (new Session())->set("flash", $this);
    }

    /**
     * filter: filtra o conteúdo da mensagem
     * @param string $message
     * @return void
     */
    private function filter($message)
    {
        return filter_variable($message, 'chars');
    }
}
