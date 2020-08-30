<?php

namespace RealWorldFrontendPhp\Model;

use \RealWorldFrontendPhp\Core\Model as CoreModel;

class Tags extends CoreModel
{
    public function prepareConnectionToFindAll(int $limit=20, array $filters=[])
    {
        return parent::prepareConnectionToFindAllEntries("tags", $limit, $filters);
    }
    
    public function parseFindAllResponse($response)
    {
        return parent::parseFindAllEntriesResponse($response, $count=false);
    }
}
