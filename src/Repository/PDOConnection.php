<?php

namespace App\Repository;

use PDO;

class PDOConnection
{

    /**@var PDO*/
    private static $pdo = null;

    /**
     * @return PDO|null
     */
    public static function getPDO()
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO ( 'mysql:dbname=' . getenv('MYSQL_DATABASE') . ';port=' . getenv('MYSQL_HOST_PORT') . ';host=' . getenv('MYSQL_DB_HOST'),
                    getenv('MYSQL_USER'),
                   getenv('MYSQL_PASSWORD'));
                self::$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                self::$pdo->exec("set names utf8");
            } catch (\PDOException $e) {
                echo "Something went Wrong - " . $e->getMessage();
            }
        }

        return self::$pdo;
    }
}
