<?php

namespace RealWorldFrontendPhp\Core;

abstract class Controller
{    
    protected $view;
    protected $api;
    
    public function __construct(ConduitApi $api)
    {
        $this->view = new View();
        $this->api = $api;
    }
}
