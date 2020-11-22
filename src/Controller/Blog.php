<?php

namespace RealWorldFrontendPhp\Controller;

use \RealWorldFrontendPhp\Core\Controller as CoreController;
use \RealWorldFrontendPhp\Exception\AuthException;
use \RealWorldFrontendPhp\Model\Articles as ArticlesModel;

class Blog extends CoreController
{
    public function createArticlePage()
    {
        $user = $this->session->getUser();

        if ($user->isGuest()) {
            throw new AuthException("User must be authorized to create articles");
        }
        
        return $this->view->renderPage("Editor", []);
    }
    
    public function editArticlePage(string $articleSlug)
    {
        $user = $this->session->getUser();

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
        
        return $this->view->renderPage("Editor", compact("article"));
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
        } catch (AppException $e) {
            $this->session->setFlash("errors", $e->getErrors());
            $this->session->setFlash("filled", (object)$articleData);       
        }        
        
        $redirectTo = !empty($article->slug) ? "/article/{$article->slug}" : "/";
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
