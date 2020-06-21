<?php

namespace Lib;

use App\Models\Auth;

class Authenticator
{
    /**
     * Verifica se o Token é válido
     *
     * @return void
     */
    public static function validateUser()
    {
        $userModel = new Auth();
        if (!isset($_SERVER['HTTP_TOKEN'])) {
            self::response('Failed to authenticate');
        }

        if (!empty($userModel->validateUser($_SERVER['HTTP_TOKEN']))) {
            self::response('Failed to authenticate');
        }
    }

    /**
     * Retorna a mensagem caso a autenticação falhe
     *
     * @param string $message
     * @return json
     */
    private static function response(string $message)
    {
        header("Content-type: application/json; charset=utf-8");
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => $message]);
        die();
    }

    /**
     * Retorna o id do usuário logado
     *
     * @return void
     */
    public static function getCurrentUser()
    {
        $user = new Auth();
        $userId = $user->getUserByToken($_SERVER['HTTP_TOKEN']);
        return $userId;
    }
}
