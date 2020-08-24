<?php

namespace RealWorldFrontendPhp\Core;

class Router
{    
    /*
        $path is request path, such as "/path/to/example/page"
        $routes is an array of routes for matching. Each route is a string with possible placeholder for variable. Example:
        [
            "/",
            "/some/path"
            "/some/path/with/named/:variable/included,
            "/other/path
        ]
    */
    
    public static function defineRoute(string $path, array $routes) : string
    {
        $pathParts = explode("/", $path);
        $definedRoute = null;
        
        // Routes with max amount of elements go first
        usort($routes, function($someRoute, $otherRoute) { 
            return substr_count($otherRoute, "/") <=> substr_count($someRoute, "/");
        });
        
        foreach ($routes as $route) {
            $routeParts = explode("/", $route);
            $isMatched = false;
            
            if (count($pathParts) >= count($routeParts)) {
                $isMatched = true;
                
                foreach ($routeParts as $i=>$routePart) {
                    $routeValue = substr($routePart, 0, 1) !== ":" ? $routePart : null; 
                    $pathValue = $pathParts[$i];
                    
                    if (!is_null($routeValue) && $pathValue !== $routeValue) {
                        $isMatched = false;
                        break;
                    }
                }
            }
            
            if ($isMatched) {
                $definedRoute = $route;
                break;
            }
        }
        
        if (is_null($definedRoute)) {
            throw new \LogicException("Unable to define route for path: {$path}. Routes: " . implode(PHP_EOL, $routes));
        }
        
        return $definedRoute;        
    }
    
    public static function extractVars(string $path, string $route, $asAssoc=false)
    {
        $vars = [];
                
        if (strpos($route, ":") === false) { // route does not contain any vars
            return $vars; 
        }
        
        $pathParts = explode("/", $path);
        $routeParts = explode("/", $route);

        foreach ($routeParts as $i=>$routePart) {
            if (substr($routePart, 0, 1) === ":") {
                $name = substr($routePart, 1);
                $value = $pathParts[$i];
                
                if ($asAssoc) {
                    $vars[$name] = $value;
                } else {
                    $vars[] = $value;
                }
            }
        }
        
        return $vars;
    }
}
