<?php

namespace RealWorldFrontendPhp\Core;

abstract class GlobalWidget extends Widget
{
    protected $request;
    protected $session;    

    
    
    
    
    public function __construct(Request $request, Session $session)
    {
        $this->request = $request;
        $this->session = $session;
        
        parent::__construct();
    }
}
