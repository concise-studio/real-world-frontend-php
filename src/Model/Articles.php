<?php

namespace RealWorldFrontendPhp\Model;

use \RealWorldFrontendPhp\Core\Model as CoreModel;
use \RealWorldFrontendPhp\Core\Pagination as Pagination;

class Articles extends CoreModel
{
    public function prepareConnectionToFindAll(?Pagination $pagination=null, array $filters=[])
    {
        return parent::prepareConnectionToFindAllEntries("articles", $pagination, $filters);
    }
    
    public function prepareConnectionToFindAllPersonal(?Pagination $pagination=null)
    {
        return parent::prepareConnectionToFindAllEntries("articles/feed", $pagination);
    }
    
    public function parseFindAllResponse($response, $count=true)
    {
        list($articles, $total) = parent::parseFindAllEntriesResponse($response, $count);
        $defaultAuthorImage = "https://static.productionready.io/images/smiley-cyrus.jpg";
        
        foreach ($articles as &$article) {
            if (empty($article->author->image)) {
                $article->author->image = $defaultAuthorImage;
            }
        }
        
        return [$articles, $total];
    }    
}
