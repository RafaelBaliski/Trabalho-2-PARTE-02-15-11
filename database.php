<?php
class Database {
    private const HOST = '127.0.0.1';
    private const DB = 'crud_db';
    private const USER = 'root';
    private const PASS = '';
    public static function connect(): PDO {
        static $pdo = null;
        if ($pdo === null) {
            $pdo = new PDO('mysql:host='.self::HOST.';dbname='.self::DB.';charset=utf8mb4', self::USER, self::PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        }
        return $pdo;
    }
}
