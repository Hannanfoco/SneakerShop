<?php
// class Database {
//     private static $host = '127.0.0.1';
//     private static $dbName = 'SneakerShop';
//     private static $username = 'root';
//     private static $password = 'hannan12';
//     private static $connection = null;

//     public static function connect(): mixed {
//         if (self::$connection === null) {
//             try {
//                 self::$connection = new PDO(
//                     dsn: "mysql:host=" . self::$host . ";dbname=" . self::$dbName,
//                     username: self::$username,
//                     password: self::$password,
//                     options: [
//                         PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
//                         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
//                     ]
//                 );
//             } catch (PDOException $e) {
//                 die("Connection failed: " . $e->getMessage());
//             }
//         }
//         return self::$connection;
//     } 

    // JWT Secret Key Definition
// public static function JWT_SECRET() {
//     return 'f38a7c0df41e2cb3b859d6454b7f89ad0d2cabe57fa9f04802a0eb94e122f3e6';
// }

// }

// ?>
