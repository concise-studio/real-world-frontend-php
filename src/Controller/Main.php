<?php

namespace RealWorldFrontendPhp\Controller;

use RealWorldFrontendPhp\Core\Controller as CoreController;
use RealWorldFrontendPhp\Core\Pagination as Pagination;
use RealWorldFrontendPhp\Model\Articles as ArticlesModel;
use RealWorldFrontendPhp\Model\Tags as TagsModel;

class Main extends CoreController
{
    public function mainPage()
    {
        $articlesPagination = Pagination::fromRequest($this->request);
        $articlesModel = new ArticlesModel($this->api);
        $tagsModel = new TagsModel($this->api);
        $apiResponses = $this->retrieveData([
            $articlesModel->prepareConnectionToFindAll($articlesPagination),
            $tagsModel->prepareConnectionToFindAll()
        ]);
        
        list($articlesResponse, $tagsResponse) = $apiResponses;
        list($articles, $totalArticles) = $articlesModel->parseFindAllResponse($articlesResponse);
        $tags = $tagsModel->parseFindAllResponse($tagsResponse);
        $articlesPagination->setTotalEntries($totalArticles);
        
        return $this->view->renderPage("Main", compact("articles", "articlesPagination", "tags"));
    }
}
