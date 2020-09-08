<?php

namespace RealWorldFrontendPhp\Controller;

use RealWorldFrontendPhp\Core\Request as Request;
use RealWorldFrontendPhp\Core\Flash as Flash;
use RealWorldFrontendPhp\Core\Controller as CoreController;
use RealWorldFrontendPhp\Core\AppException as AppException;
use RealWorldFrontendPhp\Model\Auth as AuthModel;

class Auth extends CoreController
{
    public function loginPage()
    {
        $filled = Flash::get("filled");
        $errorMessages = Flash::get("errors", []);

        return $this->view->renderPage("Login", compact("errorMessages", "filled"));
    }
    
    public function registerPage()
    {
        $filled = Flash::get("filled");
        $errorMessages = Flash::get("errors", []);

        return $this->view->renderPage("Register", compact("errorMessages", "filled"));
    }
    
    
    
    
    
    public function doRegistration()
    {
        try {
            list($username, $email, $password) = $this->extractRegisterVarsFromRequest();
            $authModel = new AuthModel($this->api);
            $authModel->register($username, $email, $password);
            $redirectTo = Request::getSanitizedQueryStringVar("redirectTo", "/");
            $this->redirect($redirectTo);
        } catch (AppException $e) {
            Flash::set("errors", $e->getErrors());
            Flash::set("filled", $this->extractStorableRegisterVarsFromRequest());
            $this->redirectBack();
        } catch (\Throwable $e) {
            Flash::add("errors", "Unknown server error");
            Flash::set("filled", $this->extractStorableRegisterVarsFromRequest());
            $this->redirectBack();
        }
    }
    
    public function doLogin()
    {
        try {
            list($email, $password) = $this->extractLoginVarsFromRequest();
            $authModel = new AuthModel($this->api);
            $authModel->login($email, $password);
            $redirectTo = Request::getSanitizedQueryStringVar("redirectTo", "/");
            $this->redirect($redirectTo);
        } catch (AppException $e) {
            Flash::set("errors", $e->getErrors());
            Flash::set("filled", $this->extractStorableRegisterVarsFromRequest());
            $this->redirectBack();
        }         
    }
    
    
    
    
    
    private function extractRegisterVarsFromRequest()
    {
        $username = Request::getSanitizedBodyVar("username");
        $email = Request::getSanitizedBodyVar("email");
        $password = Request::getBodyVar("password");
        
        return [$username, $email, $password];
    }
    
    private function extractLoginVarsFromRequest()
    {
        $email = Request::getSanitizedBodyVar("email");
        $password = Request::getBodyVar("password");
        
        return [$email, $password];
    }
    
    private function extractStorableRegisterVarsFromRequest()
    {
        $filled = Request::getBodyVars();
        unset($filled['password']);
        
        return $filled;
    }
}
