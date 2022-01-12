<?php

namespace RealWorldFrontendPhp\Core;

class Request
{
    protected $uri;
    protected $body;
    
    
    
    
    
    public function __construct(string $uri, array $body=[])
    {
        $this->uri = $uri;
        $this->body = $body;
    }

    
    
    
    
    public function getUri() : string
    {
        return $this->uri;       
    }
    
    public function getPath() : string
    {
        list($path, ) = explode("?", $this->getUri());
        
        return $path; 
    }
    
    public function getQueryString() : ?string
    {
        $uriParts = explode("?", $this->getUri());
        $queryString = $uriParts[1] ?? "";
        
        return $queryString;    
    }
    
    public function getQueryStringVars()
    {
        $queryString = $this->getQueryString();
        parse_str($queryString, $vars);
        
        return $vars;
    }
    
    public function getBodyVars()
    {
        return $this->body;
    }
    

    
    
    
    public function getBodyVar(string $name, $default=null)
    {
        $bodyVars = $this->getBodyVars();
        $value = array_key_exists($name, $bodyVars) ? $bodyVars[$name] : $default;
        
        return $value;
    }
    
    public function getQueryStringVar(string $name, $default=null)
    {
        $queryStringVars = $this->getQueryStringVars();
        $value = array_key_exists($name, $queryStringVars) ? $queryStringVars[$name] : $default;
        
        return $value;
    }
}
