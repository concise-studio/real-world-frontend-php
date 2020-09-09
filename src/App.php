<?php

namespace RealWorldFrontendPhp;

class App
{
    public function run() 
    {        
        $this->transformPhpErrorsToExceptions();
        $this->registerAutoload();
        
        try {
            $routeMap = $this->getRouteMap();
            $request = new Core\Request($_SERVER['REQUEST_URI'], $_POST);            
            $requestPath = $request->getPath();
            $route = Core\Router::defineRoute($requestPath, array_keys($routeMap));
            $vars = Core\Router::extractVars($requestPath, $route);
            $session = new Core\Session();
            $authorizationToken = $session->getUser()->getToken();
            $api = new Core\ConduitApi($authorizationToken);
            $cache = new Core\Cache();
            list($controllerName, $action) = $routeMap[$route];
            $controller = new $controllerName($request, $session, $api, $cache);
            $content = call_user_func_array([$controller, $action], $vars);
            
            echo $content;
        } catch (\Throwable $e) {
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

    
    
    
    
    
    private function getRouteMap()
    {
        $map = [
            '/'                                 => ["Main", "mainPage"],
            '/login'                            => ["Auth", "loginPage"],
            '/register'                         => ["Auth", "registerPage"],
            '/settings'                         => ["Profile", "settingsPage"],
            '/editor'                           => ["Blog", "createArticlePage"],
            '/editor/:articleSlug'              => ["Blog", "editArticlePage"],
            '/article/:articleSlug'             => ["Blog", "viewArticlePage"],
            '/profile/:username'                => ["Profile", "viewProfilePage"],
            '/profile/:username/favorites'      => ["Profile", "viewFavoritesPage"],
            
            '/do-registration'                  => ["Auth", "doRegistration"],
            '/do-login'                         => ["Auth", "doLogin"],
            '/blog/delete-article'              => ["Blog", "deleteArticle"],
            '/blog/add-comment-to-article'      => ["Blog", "addCommentToArticle"],
            '/blog/delete-comment-from-article' => ["Blog", "deleteCommentFromArticle"],
            '/profile/save-settings'            => ["Profile", "saveSettings"]
        ];
        
        $map = array_map(function($executable) { 
            $executable[0] = "\RealWorldFrontendPhp\Controller\\{$executable[0]}";
            
            return $executable;
        }, $map);
                   
        return $map;
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
}
