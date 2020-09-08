<?php

namespace RealWorldFrontendPhp\Core;

class Flash
{
    protected static $prefix = "_flash-";
    
    
    
    
    
    public static function set(string $name, $value)
    {
        $name = Flash::$prefix . $name;
        Session::set($name, $value);      
    }
    
    public static function add(string $name, $value)
    {
        $name = Flash::$prefix . $name;
        $data = Session::get($name, []);
        $data[] = $value;
        Session::set($name, $data); 
    }
    
    public static function get(string $name, $default=null)
    {
        $name = Flash::$prefix . $name;
        $data = Session::get($name, $default);
        Session::remove($name);
        
        return $data;
    }
}
