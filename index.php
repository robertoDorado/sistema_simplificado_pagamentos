<?php

ob_start();

require __DIR__ . '/vendor/autoload.php';

use Source\Core\MyRouter;

setlocale(LC_ALL, 'en_US.UTF-8');
date_default_timezone_set("America/Sao_Paulo");

$path = "./Logs/error.log";

if (!file_exists($path)) {
    mkdir("Logs");
    file_put_contents($path, '');
}

error_reporting(E_ALL & (~E_NOTICE | ~E_USER_NOTICE));
ini_set('error_log', $path);
ini_set('log_errors', true);

$route = new MyRouter(url(), ":");

/**
 * Home Route
 */
$module = null;
$route->namespace("Source\Controllers");
$route->group($module);
$route->get("/", "Home:index");

/**
 * Error Route
 */
$module = "ops";
$route->namespace("Source\Controllers");
$route->group($module);
$route->get("/error/{error_code}", "Home:error");


/**
 * Route
 */
$route->dispatch();

$route->error();

ob_end_flush();