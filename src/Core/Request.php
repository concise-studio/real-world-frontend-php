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
    
    public static function getQueryString() : ?string
    {
        $uri = Request::getUri();
        $uriParts = explode("?", $uri);
        $queryString = $uriParts[1] ?? null;
        
        return $queryString;    
    }
    
    public static function getQueryStringVars()
    {
        $queryString = Request::getQueryString();
        parse_str($queryString, $vars);
        
        return $vars;
    }
    
    public static function getBodyVars()
    {
        $vars = $_POST;
         
        return $vars;
    }
    
    public static function getVar(array $source, string $name, $default=null)
    {
        $value = array_key_exists($name, $source) ? $source[$name] : $default;
        
        return $value;
    }
    
    public static function getBodyVar(string $name, $default=null)
    {
        $source = Request::getBodyVars();
        $value = Request::getVar($source, $name, $default);
        
        return $value;
    }
    
    public static function getSanitizedVar(array $source, string $name, $default=null)
    {
        $value = Request::getVar($source, $name, $default);
        $value = Ancillary::sanitize($value);
        
        return $value;
    }
    
    public static function getSanitizedQueryStringVar(string $name, $default=null) 
    {
        $source = Request::getQueryStringVars();
        $value = Request::getSanitizedVar($source, $name, $default);
        
        return $value;
    }
    
    public static function getSanitizedBodyVar(string $name, $default=null) 
    {
        $source = Request::getBodyVars();
        $value = Request::getSanitizedVar($source, $name, $default);
        
        return $value;
    }
}
