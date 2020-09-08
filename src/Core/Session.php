<?php

namespace RealWorldFrontendPhp\Core;

class Session
{        
    public static function set(string $name, $value) : void
    {
        $_SESSION[$name] = $value;
    }
    
    public static function get(string $name, $default=null)
    {
        return array_key_exists($name, $_SESSION) ? $_SESSION[$name] : $default;
    }
    
    public static function remove(string $name)
    {
        unset($_SESSION[$name]);
    }
}
