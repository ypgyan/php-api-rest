<?php

/**
 * Registro das rotas do sistema
 */

/**
 * Rota de login
 */
$router->post('/login', 'App\Controllers\LoginController::login');
$router->get('/teste', function () {
    return ("Rota acessada com sucesso");
});

/**
 * Rotas de usuário
 */
$router->get('/users', 'App\Controllers\UserController::index');
$router->post('/users', 'App\Controllers\UserController::create');
$router->get('/users/{id}', 'App\Controllers\UserController::show');
$router->put('/users/{id}', 'App\Controllers\UserController::update');
$router->delete('/users/{id}', 'App\Controllers\UserController::delete');

/**
 * Rotas de Drink
 */
$router->post('/users/{id}/drink', 'App\Controllers\DrinkController::drink');
$router->get('/users/{id}/drink-history', 'App\Controllers\DrinkController::userHistory');
$router->get('/drink-ranking', 'App\Controllers\DrinkController::ranking');
