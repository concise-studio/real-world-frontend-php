<?php

namespace RealWorldFrontendPhp\Core;

abstract class Controller
{    
    protected $request;
    protected $session;
    protected $api;
    protected $cache;    
    protected $view;




    
    public function __construct(Request $request, Session $session, ConduitApi $api, Cache $cache) 
    {
        $this->request = $request;
        $this->session = $session;
        $this->api = $api;        
        $this->cache = $cache;
        
        $this->view = new View();
        $this->view->addVars(compact("request", "session"));
    }
    
    
    
    
    
    public function retrieveData(array $connections)
    {
        $user = $this->session->getUser();
        $order = [];
        $retrieved = [];
        $awaiting = [];
        $response = [];
        
        foreach ($connections as $i=>$connection) {
            $connectionName = md5($user->getToken() . json_encode(curl_getinfo($connection)));
            $order[$connectionName] = $i;
            $cached = $this->cache->fetch($connectionName);
            
            if (!empty($cached)) {
                $retrieved[$connectionName] = $cached;
            } else {
                $awaiting[$connectionName] = $connection;
            }
        }
        
        if (!empty($awaiting)) {
            $fromApi = $this->api->executeBatch($awaiting);
            
            foreach ($fromApi as $connectionName=>$datumFromApi) {
                $this->cache->store($connectionName, $datumFromApi);
                $retrieved[$connectionName] = $datumFromApi;
            }
        }
        
        foreach ($retrieved as $connectionName=>$retrievedDatum) {            
            $index = $order[$connectionName];
            $response[$index] = $retrievedDatum;
        }
        
        return $response;
    }
    
    
    
    
    
    public function redirect($url)
    {
        header("Location: {$url}");
        die();        
    }
    
    public function redirectBack()
    {
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
