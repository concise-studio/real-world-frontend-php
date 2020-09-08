<?php

namespace RealWorldFrontendPhp\Core;

class Ancillary
{    
    public static function arrayMapRecursive ($callback, $array) {
        $func = function ($item) use (&$func, &$callback) {
            return is_array($item) ? array_map($func, $item) : call_user_func($callback, $item);
        };

        return array_map($func, $array);
    }
    
    public static function sanitize($value)
    {
        return (is_array($value)
            ? Ancillary::arrayMapRecursive(function($v) { return htmlentities(strip_tags($v)); }, $value) // sanitize each value
            : htmlentities(strip_tags($value))
        );     
    }
}
