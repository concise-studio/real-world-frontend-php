<?php

namespace RealWorldFrontendPhp\Core;

abstract class Model
{
    protected $api;
    
    public function __construct(ConduitApi $api)
    {
        $this->api = $api;
    }
    
    public function prepareConnectionToFindAllEntries(string $entity, ?Pagination $pagination=null, array $filters=[])
    {
        if (!is_null($pagination)) {
            $limit = $pagination->perPage();
            $offset = $pagination->offset();
        } else {
            $limit = 20;
            $offset = 0;
        }
        
        $params = array_merge(compact("limit", "offset"), $filters);
        $connection = $this->api->prepareConnection("get", $entity, $params);
        
        return $connection;
    }
    
    public function parseFindAllEntriesResponse($response, bool $count=true)
    {
        $simplified = array_values((array)$response);
        $entries = $simplified[0];
        
        if ($count) {
            if (!array_key_exists(1, $simplified)) {
                throw new \RuntimeException("Server did not provide information about quantity");
            }

            $total = $simplified[1];
            
            return [$entries, $total];
        } else {
            return $entries;
        }  
    }
}
