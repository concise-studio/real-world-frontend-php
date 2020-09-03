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
            return $this->data->{$name}->value;
        }
    }
    
    public function store($name, $value) : void
    {
        $this->data->{$name} = (object)[
            'created' => time(),
            'value'   => $value
        ];
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
            $loaded = json_decode(file_get_contents($this->file));
            $this->data = $this->removeExpiredItems($loaded);
        }
    }
    
    private function removeExpiredItems(\stdClass $data): \stdClass
    {
        $currentDate = time();
        
        foreach ($data as $datumName=>$datum) {
            $diff = $currentDate-$datum->created;
            $isValid = $diff < $this->expirationTime;
            
            if (!$isValid) {
                unset($data->{$datumName});
            }
        }
        
        return $data;
    }
    
    private function save() : void
    {
        if (!empty($this->data)) {
            file_put_contents($this->file, json_encode($this->data), LOCK_EX);
        }
    }
}
