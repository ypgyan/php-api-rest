<?php

namespace App\Models;

use PDO;
use Lib\Model;

class Drink
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
     * Insere a quantidade ingerida
     *
     * @param array $userData
     * @return bool
     */
    public function insert(array $drinkData): bool
    {
        try {
            $query = $this->pdo->prepare("
                INSERT INTO user_drink(
                    user_id,
                    drink_ml,
                    created_at
                ) VALUES (
                    ?, ?, DATE(NOW())
                )
            ");
            
            if ($query->execute($drinkData)) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw $e->getMessage();
        }
    }

    /**
     * Incrementa a contagem de bebidas
     * do usuário
     *
     * @param integer $contador
     * @param integer $userId
     * @return bool
     */
    public function incrementDrinkCounter(int $contador, int $userId): bool
    {
        $query = $this->pdo->prepare("
            UPDATE user SET 
                drink_counter = ?
            WHERE id = ? AND status = 1
        ");

        if ($query->execute([$contador++, $userId])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Retorna as vezes em que foi ingerido líquido
     *
     * @param integer $userId
     */
    public function getUserHistory(int $userId)
    {
        try {
            $query = $this->pdo->prepare("
                SELECT
                    CONCAT(drink_ml, ' ', 'mL') as drink,
                    DATE_FORMAT(created_at, '%d/%m/%Y') AS created_at
                FROM user_drink
                WHERE user_id = ?
                ORDER BY created_at DESC
            ");

            if ($query->execute([$userId])) {
                $history = $query->fetchAll(PDO::FETCH_ASSOC);
                return $history;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Retorna o ranking de quem ingeriu mais líquido
     *
     */
    public function getRanking()
    {
        try {
            $query = $this->pdo->prepare("
                SELECT
                    UD.user_id,
                    U.name,
                    CONCAT(SUM(UD.drink_ml), ' ', 'mL') as total,
                    SUM(UD.drink_ml) AS drink_total
                FROM user_drink UD
                LEFT JOIN user U ON U.id = UD.user_id
                WHERE UD.created_at = DATE(NOW()) AND U.status = 1
                GROUP BY UD.user_id
                ORDER BY drink_total DESC
            ");

            if ($query->execute()) {
                $ranking = $query->fetchAll(PDO::FETCH_ASSOC);
                return $ranking;
            } else {
                return false;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
