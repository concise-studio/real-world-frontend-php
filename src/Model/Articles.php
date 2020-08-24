<?php

namespace RealWorldFrontendPhp\Model;

use \RealWorldFrontendPhp\Core\Model as CoreModel;

class Articles extends CoreModel
{
    public function findAll(int $limit = 20, array $filters = [])
    {
        return parent::findAllEntries("articles", $limit, $filters);
    }
}
