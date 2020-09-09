<?php

namespace RealWorldFrontendPhp\Core;

class Session
{      
    protected $userKey = "_user";
    protected $flashPrefix = "_flash-";
    
    
    
    
    
    public function __construct()
    {   
        if (!session_id()) {
            session_start();
        }
    }
    
    



    public function set(string $name, $value) : void
    {
        $_SESSION[$name] = $value;
    }
    
    public function get(string $name, $default=null)
    {
        return array_key_exists($name, $_SESSION) ? $_SESSION[$name] : $default;
    }
    
    public function remove(string $name)
    {
        unset($_SESSION[$name]);
    }
    
    
    
    
    
    public function setFlash(string $name, $value) : void
    {
        $key = $this->flashPrefix . $name;        
        $this->set($key, $value);   
    }
    
    public function addFlash(string $name, $value) : void
    {
        $key = $this->flashPrefix . $name;
        $values = $this->get($key, []);
        $values[] = $value;
        $this->set($key, $values); 
    }
    
    public function getFlash(string $name, $default=null)
    {
        $key = $this->flashPrefix . $name;
        $value = $this->get($key, []);
        $this->remove($key);
        
        return $value;
    }
    
    
    
    
    
    public function setUser(User $user) : void
    {
        $this->set($this->userKey, $user);
    }
    
    public function getUser() : User
    {
        $user = $this->get($this->userKey);
                
        if (is_null($user)) {
            $user = new User();
        }
        
        return $user;
    }
}
