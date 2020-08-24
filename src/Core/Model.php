<?php

namespace RealWorldFrontendPhp\Core;

abstract class Model
{
    protected $api;
    
    public function __construct(ConduitApi $api)
    {
        $this->api = $api;
    }
    
    protected function findAllEntries(string $entity, int $limit=20, array $filters=[], $count=true)
    {
        $params = array_merge(compact("limit"), $filters);
        $response = $this->api->execute("get", "/{$entity}", $params);
        $entries = $response->{$entity};
        
        if ($count) {
            $totalProperty = $entity . "Count";
            $total = $response->{$totalProperty};
            
            return [$entries, $total];
        } else {
            return $entries;
        }
    }
}
