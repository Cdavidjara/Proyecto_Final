<?php

class MySQLConnection
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $config = require __DIR__ . '/../../../config/config.php';
        $db = $config['db'];

        if ($db['driver'] === 'pgsql') {
            $dsn = "pgsql:host={$db['host']};port={$db['port']};dbname={$db['name']}";
        } else {
            $dsn = "mysql:host={$db['host']};port={$db['port']};dbname={$db['name']};charset=utf8";
        }

        $this->connection = new PDO(
            $dsn,
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
