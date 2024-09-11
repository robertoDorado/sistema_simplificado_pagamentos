<?php
namespace Source\Core;

/**
 * Server Core
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Core
 */
class Server
{
    /** @var array Server */
    private array $server;

    /**
     * Server constructor
     */
    public function __construct()
    {
        $this->server = $_SERVER;
    }

    public function getAllServerData()
    {
        return $this->server;
    }

    public function getServerByKey(string $key, $default = null)
    {
        return isset($this->server[$key]) ? $this->server[$key] : $default;
    }
}
