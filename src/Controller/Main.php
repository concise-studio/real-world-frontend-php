<?php

namespace RealWorldFrontendPhp\Controller;

use RealWorldFrontendPhp\Core\Controller as CoreController;
use RealWorldFrontendPhp\Model\Articles as ArticlesModel;

class Main extends CoreController
{
    public function mainPage()
    {
        $articlesModel = new ArticlesModel($this->api);
        list($articles, $total) = $articlesModel->findAll(); 
        
        return $this->view->renderPage("Main", compact("articles", "total"));
    }
}
