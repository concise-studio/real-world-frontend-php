<?php

namespace RealWorldFrontendPhp\Model;

use \RealWorldFrontendPhp\Core\User as CoreUser;
use \RealWorldFrontendPhp\Core\Model as CoreModel;

class Profile extends CoreModel
{
    public function prepareConnectionToFindOne(string $username)
    {
        return $this->api->prepareConnection("get", "profiles/{$username}");
    }
    
    public function parseFindOneResponse($response)
    {
        return $response->profile;
    }
}
