<?php

namespace RealWorldFrontendPhp\Core;

final class User
{    
    private static $isInitialized = false;
    private static $payload;

    
    

    
    public static function isGuest() : bool
    {
        User::init();
        $isGuest = empty(User::$payload);
        
        return $isGuest;
    }
    
    public static function isAuthorized() : bool
    {
        User::init();
        
        return !User::isGuest();
    }
    
    public static function getUsername() : string
    {
        User::init();
        
        return User::$payload['username'];
    }
    
    public static function getAvatar() : string 
    {
        User::init();
        
        return User::$payload['avatar'];
    }
    
    
    
    
    
    private static function init() : void
    {
        if (!User::$isInitialized) {
            $token = User::extractAuthorizationToken();
            
            if (!empty($token)) {
                $payload = JWT::decode($token); // TODO: Use https://github.com/firebase/php-jwt
                User::$payload = $payload;
            }
            
            User::$isInitialized = true;
        }
    }
    
    private static function extractAuthorizationToken() : ?string
    {
        return null; // TODO: implement it
    }
}
