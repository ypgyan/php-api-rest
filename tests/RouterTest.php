<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Lib\Router;

class RouterTest extends TestCase
{
    public function testVerificaRota()
    {
        $router = new Router('GET', '/ola-mundo');

        $router->get('/ola-mundo', function () {
            return true;
        });

        $result = $router->handler();

        $actual = $result();

        $expected = true;

        $this->assertEquals($expected, $actual);
    }

    public function testVerificaRotaInexistente()
    {
        $router = new Router('GET', '/outra-url');

        $router->get('/ola-mundo', function () {
            return true;
        });

        $result = $router->handler();

        $actual = $result;
        $expected = false;

        $this->assertEquals($expected, $actual);
    }
}
