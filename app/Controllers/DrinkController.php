<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Drink;
use App\Models\User;
use Lib\Authenticator;
use Exception;

class DrinkController extends BaseController
{
    /**
     * Metodo para aumentar a quantidade que o usuario bebeu
     *
     * @return json
     */
    public function drink($params)
    {
        try {
            Authenticator::validateUser();
            $userId = $params[1];
            $drinkData =  json_decode(file_get_contents('php://input'), true);
            
            if ((!isset($drinkData['drink_ml']) && empty($drinkData['drink_ml']))) {
                $this->sendError('Validation error', ["drink_ml empty"], 422);
            }
            $user = new User();
            
            if (!$user->verifyUserStatus($userId)) {
                $this->sendError('User Not Found', [], 404);
            }
            
            $model = new Drink();

            $data = [
                intval($userId),
                intval($drinkData['drink_ml']),
            ];

            if ($model->insert($data)) {
                $this->incrementUserDrink($userId);
                $this->sendResponse([], 'Success');
            } else {
                $this->sendError('Ops! Something went wrong', [], 500);
            }
        } catch (Exception $e) {
            $this->sendError('Ops! Something went wrong', [], 500);
        }
    }

    /**
     * Incrementa a quantidade de vezes que o usuário bebeu
     *
     * @param int $userId
     * @return void
     */
    private function incrementUserDrink(int $userId)
    {
        try {
            $user = new User();
            $userData = $user->getUser($userId);
            
            if ($userData['drink_counter']) {
                $contador = $userData['drink_counter'];
                $contador++;
            } else {
                $contador = 1;
            }
            $drink = new Drink();
            $drink->incrementDrinkCounter($contador, $userId);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Retorna o histórico de drinks do usuário
     *
     * @param array $params
     * @return json
     */
    public function userHistory(array $params)
    {
        try {
            Authenticator::validateUser();
            $userId = $params[1];
            $drink = new Drink();
            $history = $drink->getUserHistory($userId);

            if (!empty($history)) {
                $this->sendResponse($history, 'Success');
            } else {
                $this->sendError('Ops! Something went wrong', ['History not found'], 404);
            }
        } catch (Exception $e) {
            $this->sendError('Ops! Something went wrong', [], 500);
        }
    }

    /**
     * Retorna o ranking de quem bebeu mais
     *
     * @return json
     */
    public function ranking()
    {
        try {
            Authenticator::validateUser();
            $drink = new Drink();
            $usersRanking = $drink->getRanking();

            if (!empty($usersRanking)) {
                $ranking = [];
                foreach ($usersRanking as $key => $user) {
                    $ranking[] = [
                        'name' => $user['name'],
                        'total' => $user['total']
                    ];
                }
                $this->sendResponse($ranking, 'Success');
            } else {
                $this->sendError('Ops! Something went wrong', ['Ranking not found'], 404);
            }
        } catch (Exception $e) {
            $this->sendError('Ops! Something went wrong', [], 500);
        }
    }
}
