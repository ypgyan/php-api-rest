<?php

namespace App\Models;

use Lib\Model;
use PDO;

class Auth
{
    /**
     * Instancia do PDO
     *
     * @var PDO
     */
    private $pdo;

    /**
     * Construtor da classe
     */
    public function __construct()
    {
        $this->pdo = Model::PDOInstance();
    }

    /**
     * Verifica se o usuário existe pelo token
     *
     * @param string $token
     * @return void
     */
    public function validateUser(string $token)
    {
        $query = $this->pdo->prepare("
            SELECT
                id
            FROM user 
            WHERE token = ? AND status = 1
        ");

        if ($query->execute([$token])) {
            $user = $query->fetch();
            if (!empty($user)) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retorna o id do usuario logado
     *
     * @param string $token
     */
    public function getUserByToken(string $token)
    {
        $query = $this->pdo->prepare("
            SELECT
                id,
                name,
                email
            FROM user 
            WHERE token = ? AND status = 1
        ");

        if ($query->execute([$token])) {
            $user = $query->fetch(PDO::FETCH_ASSOC);
            return $user;
        } else {
            return false;
        }
    }
}
