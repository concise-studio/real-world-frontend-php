<?php

namespace RealWorldFrontendPhp\Core;

class Cache
{    
    protected $data;
    protected $file;
    protected $expirationTime = 60*5; // 5 min
    
    
    
    
    
    public function __construct()
    {
        $this->file = __DIR__ . "/../../storage/cache.json";
        $this->load();
    }
    
    public function __destruct()
    {
        $this->save();
    }
    
    
    
    
    
    public function fetch($name)
    {
        if (property_exists($this->data, $name)) {
            return $this->data->{$name};
        }
    }
    
    public function store($name, $value) : void
    {
        $this->data->{$name} = $value;
    }
    
    public function remove($name) : void
    {
        if (property_exists($this->data, $name)) {
            unset($this->data->{$name});
        }
    }
    




    private function load() : void
    {
        $this->data = new \stdClass;
        
        if (file_exists($this->file)) {
            if ($this->isValid($this->file)) {
                $this->data = json_decode(file_get_contents($this->file));
            } else {
                unlink($this->file);
            }
        }
    }
    
    private function isValid(string $file)
    {
        $modificationDate = filemtime($file);
        $currentDate = time();
        $diff = $currentDate-$modificationDate;
        $isValid = $diff < $this->expirationTime;
        
        return $isValid;
    }
    
    public function save() : void
    {
        file_put_contents($this->file, json_encode($this->data), LOCK_EX);
    }
}
