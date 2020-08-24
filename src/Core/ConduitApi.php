<?php

namespace RealWorldFrontendPhp\Core;

class ConduitApi
{
    protected $apiUrl = "https://conduit.productionready.io/api/";
    protected $timeout = 5000; // milliseconds
    protected $authorizationToken;
    
    
    
    
    
    public function __construct(?string $authorizationToken=null) 
    {
        $this->authorizationToken = $authorizationToken;
    }
    
    
    
    
    
    public function execute(string $method, string $endpoint, array $queryStringVars=[], array $bodyVars = []) 
    {
        $method = strtoupper($method);
        $queryString = http_build_query($queryStringVars);

        $headers = [
            "Content-Type: application/json; charset=utf-8",
        ];
        $contextHttp = [
            'method'  => strtoupper($method),
            'timeout' => $this->timeout
        ];
        
        if (!is_null($this->authorizationToken)) {
            $headers[] = "Aauthorization: Token {$this->authorizationToken}";
        }
        
        if (in_array($method, ["PUT", "POST"])) {
            $body = http_build_query($bodyVars);
            $headers[] = "Content-Length: " . strlen($body);
            $contextHttp['content'] = $body;
        }

        $url = "{$this->apiUrl}/{$endpoint}?{$queryString}";
        $context = stream_context_create(['http'=>$contextHttp]);
        $response = file_get_contents($url, false, $context);                
        
        if ($response === false) {
            throw new \RuntimeException("Server did not respond in timeout");
        }
        
        if (!in_array(substr($http_response_header[0], -6), ["200 OK", "201 OK"])) {
            $parts = explode(" ", $http_response_header[0]);
            $code = array_shift($errorParts);
            $message = implode(" ", $parts);
            
            throw new \RuntimeException($message, $code);
        }    
        
        $decodedResponse = json_decode($response, $assoc=false, $depth=512, JSON_THROW_ON_ERROR);
        
        return $decodedResponse;
    }
}
