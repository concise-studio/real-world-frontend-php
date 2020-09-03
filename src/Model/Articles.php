<?php

namespace RealWorldFrontendPhp\Model;

use \RealWorldFrontendPhp\Core\Model as CoreModel;

class Articles extends CoreModel
{
    public function prepareConnectionToFindAll(int $limit=20, array $filters=[])
    {
        return parent::prepareConnectionToFindAllEntries("articles", $limit, $filters);
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
