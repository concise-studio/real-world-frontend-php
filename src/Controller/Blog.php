<?php

namespace RealWorldFrontendPhp\Controller;

use \RealWorldFrontendPhp\Core\Controller as CoreController;
use \RealWorldFrontendPhp\Core\AppException;
use \RealWorldFrontendPhp\Exception\AuthException;
use \RealWorldFrontendPhp\Model\Articles as ArticlesModel;

class Blog extends CoreController
{
    public function createArticlePage()
    {
        $user = $this->session->getUser();
        $article = (object)$this->session->getFlash("filled");
        $errorMessages = $this->session->getFlash("errors", []);   

        if ($user->isGuest()) {
            throw new AuthException("User must be authorized to create articles");
        }
        
        return $this->view->renderPage("Editor", compact("errorMessages", "article"));
    }
    
    public function editArticlePage(string $articleSlug)
    {
        $user = $this->session->getUser();
        $filled = $this->session->getFlash("filled");
        $errorMessages = $this->session->getFlash("errors", []);   

        if ($user->isGuest()) {
            throw new AuthException("User must be authorized to edit articles");
        }
        
        $articlesModel = new ArticlesModel($this->api);
        $articlesConnection = $articlesModel->prepareConnectionToFindOne($articleSlug);
        list($articlesResponse) = $this->retrieveData([$articlesConnection]);   
        $article = $articlesModel->parseFindOneResponse($articlesResponse);
        
        if (!$this->isUserArticlesAuthor($user, $article)) {
            throw new AuthException("User must be an article's author to edit it");
        }
        
        if (!empty($filled)) {
            $article = (object)array_merge((array)$article, (array)$filled);
        }
        
        return $this->view->renderPage("Editor", compact("article", "errorMessages", "filled"));
    }
    
    public function viewArticlePage(string $articleSlug)
    {    
        $user = $this->session->getUser();    
        $articlesModel = new ArticlesModel($this->api);
        $articlesConnection = $articlesModel->prepareConnectionToFindOne($articleSlug);
        list($articlesResponse) = $this->retrieveData([$articlesConnection]);   
        $article = $articlesModel->parseFindOneResponse($articlesResponse);
        $isAuthor = $this->isUserArticlesAuthor($user, $article);
        
        return $this->view->renderPage("Article", compact("article", "isAuthor"));
    }
    
    
    
    
    
    public function publishArticle()
    {
        try {
            $articleData = $this->extractArticleDataFromRequest();
            $articlesModel = new ArticlesModel($this->api);            
            $article = (empty($articleData['slug']) 
                ? $articlesModel->create($articleData) 
                : $articlesModel->update($articleData)
            );
            $this->redirect("/article/{$article->slug}");
        } catch (AppException $e) {
            $this->session->setFlash("errors", $e->getErrors());
            $this->session->setFlash("filled", (object)$articleData);
            $this->redirectBack();
        }
    }
    
    public function deleteArticle(string $slug)
    {
        try {
            $articlesModel = new ArticlesModel($this->api);    
            $articlesModel->delete($slug);  
        } catch (AppException $e) {
            $this->session->setFlash("errors", $e->getErrors()); 
        }        
        
        $redirectTo = $this->request->getQueryStringVar("redirectTo", "/");
        $this->redirect($redirectTo);
    }
    
    
    
    
    
    private function extractArticleDataFromRequest()
    {
        $articleFields = ["slug", "title", "description", "body", "tagList"];
        $articleData = array_intersect_key($this->request->getBodyVars(), array_flip($articleFields));
        
        if (!empty($articleData['tagList'])) {
            $articleData['tagList'] = explode(" ", $articleData['tagList']);
        }
        
        return $articleData;
    }
    
    private function isUserArticlesAuthor($user, $article) 
    {
        return $user->getUsername() === $article->author->username;
    }
}
