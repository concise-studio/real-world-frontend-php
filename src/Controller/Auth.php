<?php

namespace RealWorldFrontendPhp\Controller;

use \RealWorldFrontendPhp\Core\Controller as CoreController;
use \RealWorldFrontendPhp\Core\AppException as AppException;
use \RealWorldFrontendPhp\Core\User as User;
use \RealWorldFrontendPhp\Model\Auth as AuthModel;

class Auth extends CoreController
{
    public function loginPage()
    {
        $filled = $this->session->getFlash("filled");
        $errorMessages = $this->session->getFlash("errors", []);   

        return $this->view->renderPage("Login", compact("errorMessages", "filled"));
    }
    
    public function registerPage()
    {
        $filled = $this->session->getFlash("filled");
        $errorMessages = $this->session->getFlash("errors", []);

        return $this->view->renderPage("Register", compact("errorMessages", "filled"));
    }
    
    
    
    
    
    public function doRegistration()
    {
        try {
            list($username, $email, $password) = $this->extractRegisterVarsFromRequest();
            $authModel = new AuthModel($this->api);
            $user = $authModel->register($username, $email, $password);
            $this->session->setUser($user);
            $redirectTo = $this->request->getQueryStringVar("redirectTo", "/");
            $this->redirect($redirectTo);
        } catch (AppException $e) {
            $this->session->setFlash("errors", $e->getErrors());
            $this->session->setFlash("filled", $this->request->getBodyVars());
            $this->redirectBack();
        }
    }
    
    public function doLogin()
    {
        try {
            list($email, $password) = $this->extractLoginVarsFromRequest();
            $authModel = new AuthModel($this->api);
            $user = $authModel->login($email, $password);
            $this->session->setUser($user);
            $redirectTo = $this->request->getQueryStringVar("redirectTo", "/");
            $this->redirect($redirectTo);
        } catch (AppException $e) {
            $this->session->setFlash("errors", $e->getErrors());
            $this->session->setFlash("filled", $this->request->getBodyVars());
            $this->redirectBack();
        }         
    }
    
    public function doLogout()
    {
        $guest = new User();
        $this->session->setUser($guest);
        $this->redirectBack();         
    }
    
    
    
    
    
    private function extractRegisterVarsFromRequest()
    {
        $username = $this->request->getBodyVar("username");
        $email = $this->request->getBodyVar("email");
        $password = $this->request->getBodyVar("password");
        
        return [$username, $email, $password];
    }
    
    private function extractLoginVarsFromRequest()
    {
        $email = $this->request->getBodyVar("email");
        $password = $this->request->getBodyVar("password");
        
        return [$email, $password];
    }
}
