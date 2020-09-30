<?php

namespace RealWorldFrontendPhp\Widget;

use \RealWorldFrontendPhp\Core\Widget as CoreWidget;
use \RealWorldFrontendPhp\Core\Pagination as CorePagination;

class Pagination extends CoreWidget
{
    public function __invoke()
    {
        $args = func_get_args();
        $pagination = array_key_exists(0, $args) ? $args[0] : null;
        
        if (!($pagination instanceof CorePagination)) {
            throw new \InvalidArgumentException("First param must be instance of Pagination");
        }
        
        return $this->view->render("Pagination", compact("pagination"));
    }
}
