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
        return parent::parseFindAllEntriesResponse($response, $count);
    }    
}
