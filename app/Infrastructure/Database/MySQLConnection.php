<?php

class MySQLConnection
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = require __DIR__ . '/../../../config/config.php';
        $db = $config['db'];

        $this->connection = new PDO(
            "mysql:host={$db['host']};dbname={$db['name']};charset=utf8",
            $db['user'],
            $db['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new MySQLConnection();
        }
        return self::$instance->connection;
    }
}
