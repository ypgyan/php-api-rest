<?php

namespace App\Controllers;

use App\Models\User;
use App\Controllers\BaseController;
use Lib\Authenticator;
use Exception;

class UserController extends BaseController
{

    /**
     * Retorna todos os usários
     *
     * @return void
     */
    public function index()
    {
        try {
            Authenticator::validateUser();

            $pagination =  json_decode(file_get_contents('php://input'), true);
            
            $pageNumber = 1;
            $limit = 5;

            if (isset($pagination['page']) && !empty($pagination['page'])) {
                $pageNumber = $pagination['page'];
            }

            if (isset($pagination['rows']) && !empty($pagination['rows'])) {
                $limit = $pagination['rows'];
            }

            $model = new User();
            $users = $model->getAllUsers($pageNumber, $limit);
            $this->sendResponse($users, 'success');
        } catch (Exception $e) {
            $this->sendError('Ops! Something went wrong', [], 500);
        }
    }

    /**
     * Cria um novo usuário
     *
     * @return void
     */
    public function create()
    {
        try {
            $userData =  json_decode(file_get_contents('php://input'), true);
            
            if ((!isset($userData['name']) || empty($userData['name'])) || (!isset($userData['email']) || empty($userData['email'])) || (!isset($userData['password']) || empty($userData['password']))) {
                $this->sendError('Validation error', ["Name, Email or password"], 422);
            }

            $model = new User();

            if ($model->validateEmail($userData['email'])) {
                $this->sendError('This user already exists', [], 422);
            }

            $data = [
                $userData['name'],
                $userData['email'],
                password_hash($userData['password'], PASSWORD_ARGON2I),
                md5(date('d/m/Y \à\s H:i:s'))
            ];

            if ($model->insert($data)) {
                $this->sendResponse([], 'Success');
            } else {
                $this->sendError('Ops! Something went wrong', [], 500);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->sendError('Ops! Something went wrong', [], 500);
        }
    }

    /**
     * Retorna o usuário desejado
     *
     * @param array $params
     * @return json
     */
    public function show(array $params)
    {
        try {
            Authenticator::validateUser();
            $model = new User();
            $user = $model->getUser($params[1]);

            if (!empty($user)) {
                $this->sendResponse($user, 'success');
            } else {
                $this->sendError('User Not found', [], 404);
            }
        } catch (Exception $e) {
            $this->sendError('Ops! Something went wrong', [], 500);
        }
    }

    /**
     * Atualiza os dados do próprio usuário
     *
     * @param array $params
     * @return json
     */
    public function update(array $params)
    {
        try {
            Authenticator::validateUser();
            $userId = $params[1];
            $authUser = Authenticator::getCurrentUser();
            if ($userId != $authUser['id']) {
                $this->sendError('Validation Error', ['Invalid user'], 401);
            }

            $userData =  json_decode(file_get_contents('php://input'), true);
            
            if ((!isset($userData['name']) || empty($userData['name'])) || (!isset($userData['email']) || empty($userData['email'])) || (!isset($userData['password']) || empty($userData['password']))) {
                $this->sendError('Validation error', ["Name, Email or password"], 422);
            }

            $model = new User();

            if ($model->validateEmailUpdate([$userData['email'], $authUser['id']])) {
                $this->sendError('This email already exists', [], 422);
            }

            $data = [
                $userData['name'],
                $userData['email'],
                password_hash($userData['password'], PASSWORD_ARGON2I),
                $authUser['id']
            ];

            if ($model->update($data)) {
                $this->sendResponse([], 'Success');
            } else {
                $this->sendError('Ops! Something went wrong', [], 500);
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            $this->sendError('Ops! Something went wrong', [], 500);
        }
    }

    /**
     * Deleta o usuário
     *
     * @param array $params
     * @return json
     */
    public function delete(array $params)
    {
        try {
            Authenticator::validateUser();
            $userId = $params[1];
            $authUser = Authenticator::getCurrentUser();
            if ($userId != $authUser['id']) {
                $this->sendError('Validation Error', ['Invalid user'], 401);
            }
            $model = new User();
            if ($model->deleteUser($userId)) {
                $this->sendResponse([], 'success');
            } else {
                $this->sendError('Ops! Something went wrong', [], 500);
            }
        } catch (Exception $e) {
            $this->sendError('Ops! Something went wrong', [], 500);
        }
    }
}
