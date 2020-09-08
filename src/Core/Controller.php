<?php

namespace RealWorldFrontendPhp\Core;

abstract class Controller
{    
    protected $view;
    protected $api;
    protected $cache;
    
    public function __construct(ConduitApi $api, Cache $cache)
    {
        $this->view = new View();
        $this->api = $api;
        $this->cache = $cache;
    }
    
    public function retrieveData(array $connections)
    {
        $order = [];
        $retrieved = [];
        $awaiting = [];
        $response = [];
        
        foreach ($connections as $i=>$connection) {
            $connectionName = crc32(json_encode(curl_getinfo($connection)));
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
