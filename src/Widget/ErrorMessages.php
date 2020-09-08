<?php

namespace RealWorldFrontendPhp\Widget;

use \RealWorldFrontendPhp\Core\Widget as CoreWidget;

class ErrorMessages extends CoreWidget
{
    public function __invoke()
    {
        $args = func_get_args();
        $messages = array_key_exists(0, $args) ? $args[0] : [];
        
        if (empty($messages)) {
            return "";
        }
        
        return $this->view->render("ErrorMessages", compact("messages"));
    }
}
