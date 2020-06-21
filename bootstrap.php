<?php

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

/**
 * Carrega o dotenv
 */
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = new Dotenv();
    $dotenv->load(__DIR__ . '/.env');
}

/**
 * Define o método e a url
 */
$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '/';

/**
 * Inicializa o router e inclui as rotas
 */
$router = new Lib\Router($method, $path);
include_once(__DIR__ . '/routes.php');

/**
 * Método para achar a rota
 */
$result = $router->handler();

/**
 * Caso a rota não seja encontrada retorno 404
 */
if (!$result) {
    http_response_code(404);
    echo 'Página não encontrada!';
    die();
}

/**
 * Verifica se a rota é uma função anônima ou um controlador
 */
if ($result instanceof Closure) {
    echo $result($router->getParams());
} elseif (is_string($result)) {
    $result = explode('::', $result);
    $controller = new $result[0];
    $action = $result[1];
    echo $controller->$action($router->getParams());
}