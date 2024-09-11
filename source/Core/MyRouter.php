<?php

namespace Source\Core;

use CoffeeCode\Router\Router;
use Source\Models\Auth;

class MyRouter
{
    /** @var Router */
    protected $router;

    /** @var string */
    protected $group;

    /** @var array */
    protected $route;

    /**
     * @param string $url
     * @param string $separator
     */
    public function __construct(string $url, string $separator = ":")
    {
        $route = "";
        if (!empty($_GET["route"])) {
            $route = $_GET["route"];
            mb_parse_str($_SERVER['QUERY_STRING'], $queryStrings);
            unset($queryStrings["route"]);
        }

        $buildQuery = "";
        if (!empty($queryStrings)) {
            $buildQuery = "?" . http_build_query(filter_array($queryStrings));
        }

        // Validando se a URL finaliza com uma "/". Se não tiver: redireciona
        if (mb_substr($route, -1) == '/') {
            redirect(mb_substr($route, -1) . $buildQuery);
        }
        $this->router = new Router($url, $separator);
    }

    /**
     * @return \CoffeeCode\Router\Router
     */
    public function myRoute(): Router
    {
        return $this->router;
    }

    /**
     * @param string $route
     * @param $handler
     * @param string $name
     * @return void
     */
    public function post(string $route, $handler, string $name = null): void
    {
        $this->router->post($route, $handler, $name);
    }

    /**
     * @param string $route
     * @param $handler
     * @param string $name
     * @return void
     */
    public function get(string $route, $handler, string $name = null): void
    {
        $this->router->get($route, $handler, $name);
       
    }

    /**
     * @param string $route
     * @param $handler
     * @param string $name
     * @return void
     */
    public function put(string $route, $handler, string $name = null): void
    {
        $this->router->put($route, $handler, $name);
    }

    /**
     * @param string $route
     * @param $handler
     * @param string $name
     * @return void
     */
    public function patch(string $route, $handler, string $name = null): void
    {
        $this->router->patch($route, $handler, $name);
    }

    /**
     * @param string $route
     * @param $handler
     * @param string $name
     * @return void
     */
    public function delete(string $route, $handler, string $name = null): void
    {
        $this->router->delete($route, $handler, $name);
    }

    /**
     * @param string|null $group
     * @return void
     */
    public function group(?string $group = null): void
    {
        $this->router->group($group);

    }

    /**
     * @param string|null $namespace
     * @return void
     */
    public function namespace(?string $namespace = null): void
    {
        $this->router->namespace($namespace);
    }

    /**
     * @return void
     */
    public function dispatch(): void
    {
        $this->router->dispatch();
    }

    /**
     * @return void
     */
    public function error(): void
    {
        if ($this->router->error()) {
            // redirect("/ops/error/{$this->router->error()}");
            $this->router->redirect("/ops/error/{$this->router->error()}");
        }
    }
}
