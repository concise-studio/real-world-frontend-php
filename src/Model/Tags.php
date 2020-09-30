<?php

namespace RealWorldFrontendPhp\Model;

use \RealWorldFrontendPhp\Core\Model as CoreModel;
use \RealWorldFrontendPhp\Core\Pagination as Pagination;

class Tags extends CoreModel
{
    public function prepareConnectionToFindAll(?Pagination $pagination=null, array $filters=[])
    {
        return parent::prepareConnectionToFindAllEntries("tags", $pagination, $filters);
    }
    
    public function parseFindAllResponse($response)
    {
        return parent::parseFindAllEntriesResponse($response, $count=false);
    }
}
