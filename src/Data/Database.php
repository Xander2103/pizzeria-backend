<?php
//Database.php
//nodig voor verbinding met database
declare(strict_types=1);

namespace App\Data;

use PDO;

class Database
{
    public static function getConnection(): PDO
    {
        $dsn = 'mysql:host=localhost;port=3307;dbname=pizzeria;charset=utf8mb4';
        $user = 'root';
        $pass = '';

        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
    }
}