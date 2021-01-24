<?php

namespace app;

use PDO;

class Database
{
    public static PDO $pdo;

    public static function connect($host, $dbname, $user, $password)
    {
        if (!isset(Database::$pdo)) {
            Database::$pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $user, $password);
        }
    }
}
