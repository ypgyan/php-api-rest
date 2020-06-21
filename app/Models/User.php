<?php

namespace App\Models;

use PDO;
use Lib\Model;

class User
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Model::PDOInstance();
    }

    /**
     * Insere o usuário na base
     *
     * @param array $userData
     * @return bool
     */
    public function insert(array $userData)
    {
        try {
            $query = $this->pdo->prepare("
                INSERT INTO user(
                    name,
                    email,
                    password,
                    token,
                    created_at,
                    updated_at
                ) VALUES (
                    ?, ?, ?, ?, NOW(), NOW()
                )
            ");
            
            if ($query->execute($userData)) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw $e->getMessage();
        }
    }

    /**
     * Retorna os usuário pelo id
     *
     * @param integer $userId
     */
    public function getUser(int $userId)
    {
        try {
            $query = $this->pdo->prepare("
                SELECT
                    id,
                    name,
                    email,
                    drink_counter
                FROM user 
                WHERE id = ? AND status = 1
            ");

            if ($query->execute([$userId])) {
                $userData = $query->fetch(PDO::FETCH_ASSOC);
                return $userData;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Retorna os usuário pelo id
     *
     * @param integer $userId
     */
    public function getAllUsers($pageNumber = 1, $limit = 5)
    {
        $offset = ($pageNumber - 1) * $limit;
        $total = $this->getTotalUsers();
        $pages = ceil($total / $limit);

        try {
            $query = $this->pdo->prepare("
                SELECT
                    id,
                    name,
                    email,
                    drink_counter
                FROM user 
                WHERE status = 1
                LIMIT $offset, $limit
            ");

            if ($query->execute()) {
                $users = $query->fetchAll(PDO::FETCH_ASSOC);
                $pagination = [
                    'page' => $pageNumber,
                    'total_users' => $total
                ];
                return [$users, ['pagination' => $pagination]];
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Verifica se o email já esta em uso
     *
     * @param string $email
     * @return bool
     */
    public function validateEmail(string $email)
    {
        $query = $this->pdo->prepare("
            SELECT
                email
            FROM user 
            WHERE email = ? AND status = 1
        ");

        if ($query->execute([$email])) {
            $user = $query->fetch();
            if (empty($user)) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Verifica se existe alguem com o email desejado
     *
     * @param array $data
     * @return bool
     */
    public function validateEmailUpdate(array $data)
    {
        $query = $this->pdo->prepare("
            SELECT
                email
            FROM user 
            WHERE email = ? AND id != ? AND status = 1
        ");

        if ($query->execute($data)) {
            $user = $query->fetch();
            if (empty($user)) {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Atualiza o usuário
     *
     * @param integer $userId
     * @return bool
     */
    public function update(array $data): boll
    {
        $query = $this->pdo->prepare("
            UPDATE user SET 
                name = ?,
                email = ?,
                password = ?
            WHERE id = ? AND status = 1
        ");

        if ($query->execute($data)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Deleta o usuário
     *
     * @param integer $userId
     * @return bool
     */
    public function deleteUser(int $userId)
    {
        $query = $this->pdo->prepare("
            UPDATE user SET 
                status = 0
            WHERE id = ? AND status = 1
        ");

        if ($query->execute([$userId])) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Verifica se o usuário esta excluido
     *
     * @param integer $userId
     * @return void
     */
    public function verifyUserStatus(int $userId)
    {
        $query = $this->pdo->prepare("
            SELECT
                status
            FROM user 
            WHERE id = ? AND status = 1
        ");

        if ($query->execute([$userId])) {
            $user = $query->fetch(PDO::FETCH_ASSOC);
            $status = ($user) ? true : false ;
            return $status;
        } else {
            return false;
        }
    }

    /**
     * Retorna o total de usuários
     *
     * @return int
     */
    public function getTotalUsers()
    {
        $query = $this->pdo->prepare("
            SELECT 
                COUNT(id) AS total 
            FROM user
            WHERE status = 1    
        ");
        $query->execute();
        $total = $query->fetch(PDO::FETCH_ASSOC);
        return $total['total'];
    }
}
