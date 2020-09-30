<?php

namespace RealWorldFrontendPhp;

class App
{
    private $routeMap;
    
    
    
    
    
    public function __construct()
    {
        $this->transformPhpErrorsToExceptions();
        $this->registerAutoload();
        $this->initRouteMap();
    }
    
    
    
    
    
    public function run() 
    {        
        $session = new Core\Session();
                    
        try {
            $request = new Core\Request($_SERVER['REQUEST_URI'], $_POST);            
            $requestPath = $request->getPath();
            $route = Core\Router::defineRoute($requestPath, array_keys($this->routeMap));
            $vars = Core\Router::extractVars($requestPath, $route);
            $authorizationToken = $session->getUser()->getToken();
            $api = new Core\ConduitApi($authorizationToken);
            $cache = new Core\Cache();
            list($controllerName, $action) = $this->routeMap[$route];
            $controller = new $controllerName($request, $session, $api, $cache);
            $content = call_user_func_array([$controller, $action], $vars);
            
            echo $content;
        } catch (\RealWorldFrontendPhp\Exception\AuthException $e) {
            $session->setFlash("errors", [$e->getMessage()]);
            header("Location: /login");
            die();    
        } catch (\Throwable $e) {
            http_response_code(500);
            $exceptionInfo = [
                "Exception", 
                "Message: {$e->getMessage()}",
                "File: {$e->getFile()}",
                "Line: {$e->getLine()}",
                "Trace: <pre>{$e->getTraceAsString()}</pre>" 
            ];
            echo implode(nl2br(PHP_EOL), $exceptionInfo);
        }
    }
    
    
    

    
    private function registerAutoload()
    {
        spl_autoload_register(function($class) {
            $prefix = "RealWorldFrontendPhp";
            $baseDir = __DIR__;
            $length = strlen($prefix);
            
            if (strncmp($prefix, $class, $length) !== 0) {
                return;
            }

            $relativeClass = substr($class, $length);
            $file = $baseDir . str_replace("\\", "/", $relativeClass) . ".php";
            
            if (file_exists($file)) {
                require $file;
            }
        });    
    }
    
    private function transformPhpErrorsToExceptions()
    {
        set_error_handler(function($severity, $message, $file, $line) { 
            throw new \ErrorException($message, 0, $severity, $file, $line);
        });    
    }
    
    private function initRouteMap()
    {
        $this->routeMap = require __DIR__ . "/Routes.php";
    }
}
