<?php

namespace RealWorldFrontendPhp\Controller;

use RealWorldFrontendPhp\Core\Controller as CoreController;
use RealWorldFrontendPhp\Core\AppException as AppException;
use RealWorldFrontendPhp\Exception\AuthException;
use RealWorldFrontendPhp\Model\User as UserModel;

class Profile extends CoreController
{
    public function settingsPage()
    {
        $user = $this->session->getUser();

        if ($user->isGuest()) {
            throw new AuthException("User must be authorized to change his settings");
        }
        
        $filled = $this->session->getFlash("filled");   
        $settings = array_merge($user->asArray(), $filled);
        $errorMessages = $this->session->getFlash("errors", []);   
        
        return $this->view->renderPage("Settings", compact("settings", "errorMessages"));
    }
    
    public function saveSettings()
    {
        try {
            $userModel = new UserModel($this->api);
            $settings = $this->extractSettingsFromRequest();
            $user = $userModel->update($settings);
            $this->session->setUser($user);
        } catch (AppException $e) {
            $this->session->setFlash("errors", $e->getErrors());
            $this->session->setFlash("filled", $this->request->getBodyVars());            
        }
        
        $this->redirectBack();
    }
    
    
    
    
    
    private function extractSettingsFromRequest() : array
    {
        $allowed = ["email", "username", "password", "image", "bio"];
        $settings = array_intersect_key($this->request->getBodyVars(), array_flip($allowed));
        
        if (empty($settings['password'])) {
            unset($settings['password']);
        }
        
        return $settings;
    }
}
