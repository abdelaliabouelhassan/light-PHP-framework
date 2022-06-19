<?php

namespace App\core;

class Session
{

    //constructor
    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
            //unset flash message

        }
    }

    public static function start()
    {
        session_start();
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    public static function destroy()
    {
        session_destroy();
    }

    public static function flash($key, $value = '')
    {

        $_SESSION['flash_' . $key] = $value;
    }

    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public static function clear()
    {
        $_SESSION = [];
    }

    public static function regenerate()
    {
        session_regenerate_id();
    }

    public static function setExpiration($time)
    {
        session_set_cookie_params($time);
    }

    public static function setName($name)
    {
        session_name($name);
    }

    public static function setId($id)
    {
        session_id($id);
    }

    public static function getId()
    {
        return session_id();
    }

    public static function getName()
    {
        return session_name();
    }

    public static function getExpiration()
    {
        return session_get_cookie_params();
    }

    public static function getCookiePath()
    {
        return session_get_cookie_params()['path'];
    }

    public static function getCookieDomain()
    {
        return session_get_cookie_params()['domain'];
    }

    public static function getCookieSecure()
    {
        return session_get_cookie_params()['secure'];
    }

    public static function getCookieHttpOnly()
    {
        return session_get_cookie_params()['httponly'];
    }

    public static function getCookieSameSite()
    {
        return session_get_cookie_params()['samesite'];
    }

    public static function getCookieLifetime()
    {
        return session_get_cookie_params()['lifetime'];
    }
}
