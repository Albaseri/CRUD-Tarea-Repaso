<?php

namespace App\Db;

use PDO;
use PDOException;

class Conexion
{

    protected static $conexion;

    public function __construct()
    {
        self::setConexion();
    }

    protected static function setConexion()
    {
        if (self::$conexion != null) return;

        $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . "/../../");
        $dotenv->load();

        $db = $_ENV['DB'];
        $user = $_ENV['USER'];
        $pass = $_ENV['PASS'];
        $host = $_ENV['HOST'];

        $dsn = "mysql:dbname=$db;host=$host;charset=utf8mb4";
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        try {
            self::$conexion = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $ex) {
            die("Error en conexión: " . $ex->getMessage());
        }
    }
}
