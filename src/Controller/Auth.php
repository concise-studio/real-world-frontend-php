<?php

namespace RealWorldFrontendPhp\Controller;

use RealWorldFrontendPhp\Core\Controller as CoreController;

class Auth extends CoreController
{
    public function loginPage()
    {
        return $this->view->renderPage("Login");
    }
    
    public function registerPage()
    {
        return $this->view->renderPage("Register");
    }
}
