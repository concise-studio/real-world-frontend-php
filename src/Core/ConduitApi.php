<?php

namespace RealWorldFrontendPhp\Core;

final class ConduitApi
{
    protected $apiUrl = "https://api.realworld.io/api";
    protected $timeout = 5; // seconds
    protected $authorizationToken;
    
    
    
    
    
    public function __construct(?string $authorizationToken=null) 
    {
        $this->authorizationToken = $authorizationToken;
    }
    
    
    
    

    public function execute(string $method, string $endpoint, array $queryStringVars=[], array $bodyVars=[]) 
    {
        $connection = $this->prepareConnection($method, $endpoint, $queryStringVars, $bodyVars);
        $response = $this->executeBatch([$connection])[0];
        
        return $response; 
    }
    
    public function prepareConnection(string $method, string $endpoint, array $queryStringVars=[], array $bodyVars=[])
    {
        $method = strtoupper($method);
        $url = "{$this->apiUrl}/{$endpoint}";
        $connection = curl_init();
        $headers = ["Content-Type: application/json; charset=utf-8"];
        
        if (!empty($queryStringVars)) {
            $queryString = http_build_query($queryStringVars);    
            $url = "{$url}?{$queryString}";    
        }
        
        if (in_array($method, ["PUT", "POST"])) {      
            curl_setopt($connection, CURLOPT_POSTFIELDS, json_encode($bodyVars));
        } 
        
        if ($method === "POST") {
            curl_setopt($connection, CURLOPT_POST, true);
        }
        
        if (in_array($method, ["PUT", "DELETE"])) {
            curl_setopt($connection, CURLOPT_CUSTOMREQUEST, $method);
        }
        
        if (!is_null($this->authorizationToken)) {
            $headers[] = "Authorization: Token {$this->authorizationToken}";
        }
        
        curl_setopt($connection, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($connection, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($connection, CURLOPT_URL, $url);
        curl_setopt($connection, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);  
        
        return $connection;
    }
    
    public function executeBatch(array $connections) : array
    {
        $minHttpErrorCode = 100;
        $successfulResponseCodes = [200, 201, 204];
        $curl = curl_multi_init();
        $responses = [];
        
        foreach ($connections as $connection) {
            curl_multi_add_handle($curl, $connection);
        }        
        
        do {
            curl_multi_exec($curl, $running);
            curl_multi_select($curl);
        } while (
            $running > 0
        );
        
        foreach ($connections as $i=>$connection) {
            $responseCode = curl_getinfo($connection, CURLINFO_HTTP_CODE);
            $responseBody = curl_multi_getcontent($connection);
            
            if ($responseCode < $minHttpErrorCode) {
                throw new \RuntimeException("Server did not respond in timeout");
            }
            
            if (!in_array($responseCode, $successfulResponseCodes)) {
                $errorMessage = "Server responded with error. Code: {$responseCode}. Body: {$responseBody}";
                
                if (in_array($responseCode, [403, 422])) {
                    $decodedResponse = json_decode($responseBody, $assoc=false, $depth=512, JSON_THROW_ON_ERROR);
                    $e = new AppException($errorMessage);
                    $errors = $this->extractErrors($decodedResponse);
                    $e->setErrors($errors);
                    throw $e;
                }
                
                throw new \RuntimeException("Server responded with error. Code: {$responseCode}. Body: {$responseBody}", $responseCode);
            }
            
            try {
                $decodedResponse = empty($responseBody) ? null : json_decode($responseBody, $assoc=false, $depth=512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                error_log("Response body was not JSON-encoded, Error: {$e->getMessage()}. Body: " . $responseBody);
                $decodedResponse = null;
            }
            
            $responses[$i] = $decodedResponse;
            curl_multi_remove_handle($curl, $connection);
        }
        
        curl_multi_close($curl);
        
        return $responses;
    }
    
    
    
    
    
    protected function extractErrors(\stdClass $response) : array
    {
        $errors = [];
        
        foreach ($response->errors as $field=>$messages) {
            foreach ($messages as $message) {
                $errors[] = "{$field} {$message}";
            }
        }
        
        return $errors;
    }
}
