<?php

namespace RealWorldFrontendPhp\Core;

class AppException extends \RuntimeException
{
    protected $errors = [];
    
    
    
    
    
    public function setErrors(array $errors) : void
    {
        $this->errors = array_map("strval", $errors);
    }
    
    public function setError(string $error) : void
    {
        $this->errors = [$error];
    }
    
    public function addError(string $error) : void
    {
        $this->errors[] = $error;
    }
    
    public function getErrors() : array
    {
        return $this->errors;
    }
    
    public function getLastError()
    {
        $errors = $this->errors;
        $error = array_pop($errors);
        
        return $error;
    }
    
    public function getErrorsAsString(string $glue=". ") : string
    {
        return implode($glue, $this->errors);
    }
    
    public function getError(string $glue=". ") : string
    {
        return $this->getErrorsAsString($glue);
    }
}
