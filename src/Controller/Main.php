<?php

namespace RealWorldFrontendPhp\Controller;

use \RealWorldFrontendPhp\Core\Controller as CoreController;
use \RealWorldFrontendPhp\Core\Pagination as Pagination;
use \RealWorldFrontendPhp\Model\Articles as ArticlesModel;
use \RealWorldFrontendPhp\Model\Tags as TagsModel;
use \RealWorldFrontendPhp\Exception\AuthException;

class Main extends CoreController
{
    public function mainPage()
    {
        $user = $this->session->getUser();
        $articlesFeed = $this->request->getQueryStringVar("feed", "global");
        $articlesPagination = Pagination::fromRequest($this->request);
        $articlesModel = new ArticlesModel($this->api);
        $tagsModel = new TagsModel($this->api);
        $tagsConnection = $tagsModel->prepareConnectionToFindAll();
        
        if ($articlesFeed === "global") {
            $articlesFilters = $this->prepareArticlesFilters();        
            $articlesConnection = $articlesModel->prepareConnectionToFindAll($articlesPagination, $articlesFilters);
        } else {
            $articlesConnection = $articlesModel->prepareConnectionToFindAllPersonal($articlesPagination);
        }
        
        $apiResponses = $this->retrieveData([$articlesConnection, $tagsConnection]);        
        list($articlesResponse, $tagsResponse) = $apiResponses;
        list($articles, $totalArticles) = $articlesModel->parseFindAllResponse($articlesResponse);
        $tags = $tagsModel->parseFindAllResponse($tagsResponse);
        $articlesPagination->setTotalEntries($totalArticles);
        
        return $this->view->renderPage("Main", compact("user", "articlesFeed", "articles", "articlesPagination", "tags"));
    }
    
    
    
    
    
    private function prepareArticlesFilters()
    {
        $filters = [];
        $tag = $this->request->getQueryStringVar("tag");
        
        if (!empty($tag)) {
            $filters['tag'] = $tag;
        }
        
        return $filters;
    }
}
