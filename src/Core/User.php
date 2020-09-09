<?php

namespace RealWorldFrontendPhp\Core;

class User
{   
    protected $token;
    protected $username;
    protected $email;
    protected $image;
    protected $bio;
    
    
    
    
    
    public function __construct(
        ?string $token = null, 
        ?string $username = null, 
        ?string $email = null, 
        ?string $image = null, 
        ?string $bio = null
    ) {
        if (!empty($token)) {
            if (empty($username)) {
                throw new \InvalidArgumentException("username must be specified if token specified");
            }
            
            if (empty($email)) {
                throw new \InvalidArgumentException("email must be specified if token specified");
            }
        }
        
        $this->token = $token;
        $this->username = $username;
        $this->email = $email;
        $this->image = $image;
        $this->bio = $bio;
    }
    
    public static function fromArray(array $data) : User
    {
        if (
            array_key_exists("token", $data) &&
            array_key_exists("username", $data) &&
            array_key_exists("email", $data)
        ) {
            $token = (string)$data['token'];
            $username = (string)$data['username'];
            $email = (string)$data['email'];
            $image = null;
            $bio = null;
            
            if (array_key_exists("image", $data)) {
                $image = (string) $data['image'];
            }
            
            if (array_key_exists("bio", $data)) {
                $bio = (string)$data['bio'];
            }
            
            $user = new User($token, $username, $email, $image, $bio);
        } else {
            $user = new User();
        }
        
        return $user;        
    }
    
    
    
    
    
    public function isGuest() : bool
    {
        return empty($this->token);
    }
    
    public function isAuthorized() : bool
    {
        return !$this->isGuest();
    }
    
    public function getToken() : ?string
    {
        return $this->token;
    }
    
    public function getUsername() : ?string
    {
        return $this->username;
    }
    
    public function getEmail() : ?string
    {
        return $this->email;
    }
    
    public function getImage() : ?string 
    {
        return $this->image;
    }
    
    public function getBio() : ?string
    {
        return $this->bio;
    }
    
    public function getAvatar() : ?string 
    {        
        return $this->getImage();
    }
}
