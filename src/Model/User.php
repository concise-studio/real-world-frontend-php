<?php

namespace RealWorldFrontendPhp\Model;

use \RealWorldFrontendPhp\Core\User as CoreUser;
use \RealWorldFrontendPhp\Core\Model as CoreModel;

class User extends CoreModel
{
    public function update(array $settings) : CoreUser
    {
        $response = $this->api->execute("put", "user", [], ['user'=>$settings]);
        $userData = (array)$response->user;
        $user = CoreUser::fromArray($userData);

        return $user;
    }
}
