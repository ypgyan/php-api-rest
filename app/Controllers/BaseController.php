<?php

namespace App\Controllers;

class BaseController
{
    /**
     * Retorna as respostas de sucesso.
     *
     * @param array $result
     * @param string $message
     * @return void
     */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        header("Content-type: application/json; charset=utf-8");
        echo json_encode($response);
        die();
    }


    /**
     * Retorna as respostas com erro.
     *
     * @param array $result
     * @param array $errorMessages
     * @param int $code
     * @return json
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        header("Content-type: application/json; charset=utf-8");
        http_response_code($code);
        echo json_encode($response);
        die();
    }
}
