<?php
class DBConnection {
    private $dbHost = "localhost";
    private $dbUserName = "arbind";
    private $dbPassword = "aux@163$";
    private $dbName = "keyboard_app";
    private static $connection = null;

    private function __construct() {
        if (! self::$connection) {
            try {
                $conn = new PDO('mysql:host='.$this->dbHost.';dbname='.$this->dbName, $this->dbUserName,$this->dbPassword);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection = $conn;
            } catch (Exception $e) {
                die("Failed to connect with MySql: " . $e->getMessage());
            }
        }
    }

    public static function getConnection() {
        if (! self::$connection) {
            $instance = new DBConnection();
            return self::$connection;
        }
        return self::$connection;
    }

}

DBConnection::getConnection();
