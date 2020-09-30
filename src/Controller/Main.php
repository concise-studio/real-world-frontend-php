<?php

namespace RealWorldFrontendPhp\Controller;

use RealWorldFrontendPhp\Core\Controller as CoreController;
use RealWorldFrontendPhp\Core\Pagination as Pagination;
use RealWorldFrontendPhp\Model\Articles as ArticlesModel;
use RealWorldFrontendPhp\Model\Tags as TagsModel;
use RealWorldFrontendPhp\Exception\AuthException;

class Main extends CoreController
{
    public function mainPage()
    {
        $user = $this->session->getUser();
        $articlesFeed = $this->request->getQueryStringVar("feed", "global");
        $articlesTag = $this->request->getQueryStringVar("tag");
        $articlesFilters = $this->prepareArticlesFilters($articlesFeed, $articlesTag);
        $articlesPagination = Pagination::fromRequest($this->request);
        $articlesModel = new ArticlesModel($this->api);
        $tagsModel = new TagsModel($this->api);
        $apiResponses = $this->retrieveData([
            $articlesModel->prepareConnectionToFindAll($articlesPagination, $articlesFilters),
            $tagsModel->prepareConnectionToFindAll()
        ]);
        
        list($articlesResponse, $tagsResponse) = $apiResponses;
        list($articles, $totalArticles) = $articlesModel->parseFindAllResponse($articlesResponse);
        $tags = $tagsModel->parseFindAllResponse($tagsResponse);
        $articlesPagination->setTotalEntries($totalArticles);
        
        return $this->view->renderPage("Main", compact("user", "articlesFeed", "articles", "articlesPagination", "tags"));
    }
    
    private function prepareArticlesFilters($feed="global", $tag=null)
    {
        $filters = [];
        
        if ($feed === "personal") {
            $user = $this->session->getUser();
            
            if ($user->isGuest()) {
                throw new AuthException("User must be authorized to get personal feed");
            }
            
            $filters['favorited'] = $user->getUsername();
        }
        
        if (!empty($tag)) {
            $filters['tag'] = $tag;
        }
        
        return $filters;
    }
}
