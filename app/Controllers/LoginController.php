<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Login;
use Exception;

class LoginController extends BaseController
{
    /**
     * Efetua o login do usuário
     * retornando o token e seus dados
     *
     * @return json
     */
    public function login()
    {
        try {
            $loginData =  json_decode(file_get_contents('php://input'), true);

            if ((!isset($loginData['email']) && empty($loginData['email'])) || (!isset($loginData['password']) && empty($userData['password']) )) {
                $this->sendError('Validation error', ["Email or password is missing"], 422);
            }

            $user = new Login();
            $userData = $user->validateLogin($loginData);
            
            if ($userData) {
                $this->sendResponse([
                    'token' => $userData['token'],
                    'user_id' => $userData['id'],
                    'email' => $userData['email'],
                    'drink_counter' => $userData['drink_counter'],
                    'name' => $userData['name']
                ], 'Login succesfully');
            } else {
                $this->sendError('validation error', ['email or password wrong'], 422);
            }
        } catch (Exception $e) {
            $this->sendError('Ops! Something went wrong', [], 500);
        }
    }
}
