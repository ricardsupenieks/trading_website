<?php

namespace App;

class Session
{
    private static array $data;

    public static function start()
    {
        session_start();
        self::$data = $_SESSION;
    }

    public static function store(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function storeInArray(string $key, $value)
    {
        $_SESSION[$key][] = $value;
    }

    public static function has(string $key)
    {
        return isset(self::$data->$key);
    }
}