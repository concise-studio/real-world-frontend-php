<?php

namespace RealWorldFrontendPhp\Controller;

use \RealWorldFrontendPhp\Core\Controller as CoreController;
use \RealWorldFrontendPhp\Core\Pagination as Pagination;
use \RealWorldFrontendPhp\Core\AppException as AppException;
use \RealWorldFrontendPhp\Exception\AuthException;
use \RealWorldFrontendPhp\Model\Articles as ArticlesModel;
use \RealWorldFrontendPhp\Model\User as UserModel;
use \RealWorldFrontendPhp\Model\Profile as ProfileModel;

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
    
    public function viewProfilePage(string $username)
    {
        $profileModel = new ProfileModel($this->api);
        $profileConnection = $profileModel->prepareConnectionToFindOne($username);
        
        $articlesFeed = $this->request->getQueryStringVar("feed", "author");
        $articlesPagination = Pagination::fromRequest($this->request);
        $articlesModel = new ArticlesModel($this->api);
        $articlesFilters = $this->prepareArticlesFilters($articlesFeed, $username);    
        $articlesConnection =  $articlesModel->prepareConnectionToFindAll($articlesPagination, $articlesFilters);
                
        list($profileResponse, $articlesResponse) = $this->retrieveData([$profileConnection, $articlesConnection]);   
        $profile = $profileModel->parseFindOneResponse($profileResponse);
        list($articles, $totalArticles) = $articlesModel->parseFindAllResponse($articlesResponse);
        $articlesPagination->setTotalEntries($totalArticles);
        
        return $this->view->renderPage("Profile", compact("profile", "articlesFeed", "articles", "articlesPagination"));
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
    
    private function prepareArticlesFilters(string $feed, string $username)
    {
        $filters = [];
        
        if ($feed === "author") {
            $filters['author'] = $username;
        } else {
            $filters['favorited'] = $username;
        }
        
        return $filters;
    }
}
