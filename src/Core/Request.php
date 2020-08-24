<?php

namespace RealWorldFrontendPhp\Core;

class Request
{
    public static function getUri(string $default = "/") : string
    {
        return $_SERVER['REQUEST_URI'];        
    }
    
    public static function getPath() : string
    {
        $uri = Request::getUri();
        list($path, ) = explode("?", $uri);
        
        return $path; 
    }
    
    public static function getQueryString() : string
    {
        $uri = Request::getUri();
        list(, $queryString) = explode("?", $uri);        
    }
    
    public static function getQueryStringVars()
    {
        $queryString = Request::getQueryString();
        parse_str($queryString, $vars);
        
        return $vars;
    }
}
