<?php

namespace RealWorldFrontendPhp\Model;

use \RealWorldFrontendPhp\Core\User as User;
use \RealWorldFrontendPhp\Core\Model as CoreModel;

class Auth extends CoreModel
{
    public function register(string $username, string $email, string $password) : void
    {
        $info = compact("username", "email", "password");
        $response = $this->api->execute("post", "users", [], ['user'=>$info]);
        $userData = (array)$response->user; 
        
        User::set($userData);
    }
    
    public function login(string $email, string $password) : void
    {
        $credentials = compact("email", "password");
        $response = $this->api->execute("post", "users/login", [], ['user'=>$credentials]);
        $userData = (array)$response->user; 

        User::set($userData);
    }
}
