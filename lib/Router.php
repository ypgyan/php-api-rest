<?php

namespace Lib;

class Router
{
    /**
     * Armazena as rotas do sistema
     *
     * @var array
     */
    private $routes = [];

    /**
     * Método Http
     *
     * @var string
     */
    private $method;
    
    /**
     * url
     *
     * @var string
     */
    private $path;
    
    /**
     * Armazena os parametros passado via url
     *
     * @var array
     */
    private $params;

    /**
     * Construtor da classe
     *
     * @param string $method
     * @param string $path
     */
    public function __construct(string $method, string $path)
    {
        $this->method = $method;
        $this->path = $path;
    }

    /**
     * Adiciona as rotas GET
     *
     * @param string $route
     * @param $action
     * @return void
     */
    public function get(string $route, $action)
    {
        $this->add('GET', $route, $action);
    }

    /**
     * Adiciona as rotas POST
     *
     * @param string $route
     * @param $action
     * @return void
     */
    public function post(string $route, $action)
    {
        $this->add('POST', $route, $action);
    }

    /**
     * Adiciona as rotas PUT
     *
     * @param string $route
     * @param $action
     * @return void
     */
    public function put(string $route, $action)
    {
        $this->add('PUT', $route, $action);
    }

    /**
     * Adiciona as rotas DELETE
     *
     * @param string $route
     * @param $action
     * @return void
     */
    public function delete(string $route, $action)
    {
        $this->add("DELETE", $route, $action);
    }

    /**
     * Adiciona as rotas ao array de rotas
     *
     * @param string $method
     * @param string $route
     * @param $action
     * @return void
     */
    private function add(string $method, string $route, $action)
    {
        $this->routes[$method][$route] = $action;
    }

    /**
     * Retorna os parametros
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Retorna a rota desejada caso ela exista
     *
     * @return void
     */
    public function handler()
    {
        if (empty($this->routes[$this->method])) {
            return false;
        }

        if (isset($this->routes[$this->method][$this->path])) {
            return $this->routes[$this->method][$this->path];
        }

        foreach ($this->routes[$this->method] as $route => $action) {
            $result = $this->checkUrl($route, $this->path);
            if ($result >= 1) {
                return $action;
            }
        }
        return false;
    }

    /**
     * Metodo que recupera os parâmetros
     * passado na URL
     *
     * @param string $route
     * @param $path
     * @return void
     */
    private function checkUrl(string $route, $path)
    {
        preg_match_all('/\{([^\}]*)\}/', $route, $variables);

        $regex = str_replace('/', '\/', $route);

        foreach ($variables[0] as $i => $variable) {
            $replacement = '([a-zA-Z0-9\-\_\ ]+)';
            $regex = str_replace($variable, $replacement, $regex);
        }

        $regex = preg_replace('/{([a-zA-Z]+)}/', '([a-zA-Z0-9+])', $regex);

        $result = preg_match('/^' . $regex . '$/', $path, $params);

        $this->params = $params;

        return $result;
    }
}
