<?php

namespace App\Models;

use Lib\Model;
use PDO;

class Login
{
    /**
     * Instancia do PDO
     *
     * @var PDO
     */
    private $pdo;

    /**
     * Construtor
     */
    public function __construct()
    {
        $this->pdo = Model::PDOInstance();
    }

    /**
     * Valida o login
     *
     * @param array $loginData
     * @return void
     */
    public function validateLogin(array $loginData)
    {
        $query = $this->pdo->prepare("
            SELECT
                token,
                id,
                name,
                email,
                drink_counter,
                password
            FROM user
            WHERE status = 1 AND email = ?
        ");

        if ($query->execute([$loginData['email']])) {
            $user = $query->fetch(PDO::FETCH_ASSOC);

            if (password_verify($loginData['password'], $user['password'])) {
                return $user;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
