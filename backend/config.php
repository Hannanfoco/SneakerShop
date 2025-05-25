<?php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

class Config
{
    public static function DB_NAME()
    {
        return 'SneakerShop';
    }

    public static function DB_PORT()
    {
        return 3306;
    }

    public static function DB_USER()
    {
        return 'root';
    }

    public static function DB_PASSWORD()
    {
        return 'hannan12';
    }

    public static function DB_HOST()
    {
        return '127.0.0.1';
    }

    public static function JWT_SECRET()
    {
        return 'f38a7c0df41e2cb3b859d6454b7f89ad0d2cabe57fa9f04802a0eb94e122f3e6';
    }
}
