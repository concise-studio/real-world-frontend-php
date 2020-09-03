<?php

namespace RealWorldFrontendPhp\Controller;

use RealWorldFrontendPhp\Core\Controller as CoreController;
use RealWorldFrontendPhp\Model\Articles as ArticlesModel;
use RealWorldFrontendPhp\Model\Tags as TagsModel;

class Main extends CoreController
{
    public function mainPage()
    {
        $articlesModel = new ArticlesModel($this->api);
        $tagsModel = new TagsModel($this->api);
        $apiResponses = $this->retrieveData([
            $articlesModel->prepareConnectionToFindAll(),
            $tagsModel->prepareConnectionToFindAll()
        ]);
        
        list($articlesResponse, $tagsResponse) = $apiResponses;
        list($articles, $total) = $articlesModel->parseFindAllResponse($articlesResponse);
        $tags = $tagsModel->parseFindAllResponse($tagsResponse);
        
        return $this->view->renderPage("Main", compact("articles", "total", "tags"));
    }
}
