<?php

namespace Source\Core;

use Source\Core\View;
use Source\Support\Message;
use Source\Support\RequestFiles;
use Source\Support\Requests;

class Controller
{
    /** @var View */
    protected $view;

    /** @var Message */
    protected $message;

    /** @var Server Objeto Server */
    protected $server;

    /** @var RequestFiles */
    protected $fileData;

    /** @var Requests */
    protected $requests;

    /** @var Session */
    protected $session;

    /**
     * @param string $viewPath
     * @return void
     */
    public function __construct(string $viewPath = CONF_VIEW_PATH . "/" . CONF_VIEW_THEME)
    {
        $this->server = new Server();
        $this->message = new Message();
        $this->view = new View($viewPath);
        $this->fileData = new RequestFiles();
        $this->session = new Session();
        $this->session->csrf();
        $this->requests = new Requests($this->session);
    }

    public function getCurrentSession()
    {
        return $this->session;
    }

    public function getRequests(): Requests
    {
        return $this->requests;
    }

    public function getRequestFiles(): RequestFiles
    {
        return $this->fileData;
    }

    public function getServer(): Server
    {
        return $this->server;
    }
}
