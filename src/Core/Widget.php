<?php

namespace RealWorldFrontendPhp\Core;

abstract class Widget
{
    protected $view;
    
    public function __construct()
    {
        $this->view = new View();
        $this->view->setViewPath(__DIR__ . "/../View/Widget");
    }
}
