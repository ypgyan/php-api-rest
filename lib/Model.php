<?php

namespace Lib;

use PDO;

class Model extends PDO
{
    /**
     *
     * @var type
     */
    public $pdo;

    public function __construct()
    {
        $this->pdo = $this->PDOInstance();
    }

    public static function PDOInstance(Type $var = null)
    {
        try {
            return new PDO("{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset={$_ENV['DB_COLLATION']}", "{$_ENV['DB_USER']}", "{$_ENV['DB_PASS']}");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
