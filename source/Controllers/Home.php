<?php

namespace Source\Controllers;

use Source\Core\Controller;

/**
 * Home Controllers
 * @package Source\Controllers
 */
class Home extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Home
     * 
     * @return array
     */

    public function index(): void
    {
        echo $this->view->render("home", [
            "title" => "Home"
        ]);
    }

    /**
     * Error
     *
     * @param array $data
     * @return void
     */
    public function error($data = [])
    {
        echo $this->view->render("error", [
            "title" => "Error",
            "error_code" => $data['error_code']
        ]);
    }
}
