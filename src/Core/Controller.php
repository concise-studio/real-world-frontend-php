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
            $connectionName = "c" . crc32(json_encode(curl_getinfo($connection))); // add "c" at the beginning because $connectionName must be non-numeric (otherwise array_merge probably return unexpected result)
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
            $retrieved = array_merge($retrieved, $fromApi);
        }
        
        foreach ($retrieved as $connectionName=>$retrievedDatum) {
            $this->cache->store($connectionName, $retrievedDatum);
            $index = $order[$connectionName];
            $response[$index] = $retrievedDatum;
        }
        
        return $response;
    }
}
