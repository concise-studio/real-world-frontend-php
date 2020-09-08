<?php

namespace RealWorldFrontendPhp\Core;

class User
{    
    protected static $sessionKey = "_user";
    protected static $isInitialized = false;
    protected static $data;

    
    
    
    
    public static function isGuest() : bool
    {
        User::init();
        $isGuest = empty(User::$data);
        
        return $isGuest;
    }
    
    public static function isAuthorized() : bool
    {
        User::init();
        
        return !User::isGuest();
    }
    
    public static function getToken() : ?string
    {
        User::init();
        
        return User::isAuthorized() ? User::$data['token'] : null;
    }
    
    public static function getUsername() : ?string
    {
        User::init();
        
        return User::$data['username']  ?? null;
    }
    
    public static function getEmail() : ?string
    {
        User::init();
        
        return User::$data['email']  ?? null;
    }
    
    public static function getImage() : ?string 
    {
        User::init();
        
        return User::$data['image'] ?? null;
    }
    
    public static function getAvatar() : ?string 
    {        
        return User::getImage();
    }
    
    public static function getBio() : ?string
    {
        return User::$data['bio'] ?? null;
    }
    
    public static function set(array $data) : void
    {
        $required = [
            "token",
            "username",
            "email"
        ];
        
        foreach ($required as $field) {
            if (!array_key_exists($field, $data)) {
                throw new \InvalidArgumentException ("Array 'data' must contains field {$field}");
            }
        }
        
        User::$data = $data;
        User::save();
    }
    
    
    
    
    
    private static function init() : void
    {
        if (!User::$isInitialized) {
            User::$data = Session::get(User::$sessionKey, []);
            User::$isInitialized = true;
        }
    }
    
    private static function save() : void
    {
        Session::set(User::$sessionKey, User::$data);
    }
}
