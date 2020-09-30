<?php
namespace RealWorldFrontendPhp\Core;

class Pagination 
{
    private $currentPage;
    private $perPage;
    private $path;
    private $vars;
    private $totalEntries;
    
        
    
    
    
    public function __construct(int $currentPage=1, int $perPage=20, string $path="/", array $vars=[])
    {
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;        
        $this->path = $path;
        $this->vars = $vars;
    }
    
    public static function fromRequest(Request $request) : Pagination
    {     
        $currentPage = $request->getQueryStringVar("current", 1);
        $perPage = $request->getQueryStringVar("per-page", 20);
        $pagination = new Pagination($currentPage, $perPage, $request->getPath(), $request->getQueryStringVars());
        
        return $pagination;
    }
    
    
    
    
    
    public function setTotalEntries(int $totalEntries) : void
    {
        $this->totalEntries = $totalEntries;
    }
    
    
    
    
        
    public function perPage() : int
    {
        return $this->perPage;
    }
    
    public function current() : int
    {
        return $this->currentPage;
    }
    
    public function prev() : int
    {
        return $this->current() - 1;
    }
    
    public function next() : int
    {
        return $this->current() + 1;
    }
    
    public function offset()
    {
        return $this->prev() * $this->perPage();
    }
    
    public function firstEntry() : int
    {
        return $this->prev() * $this->perPage() + 1;
    }
    
    public function lastEntry() : int
    {
        return $this->current() * $this->perPage();
    }
    
    public function totalEntries() : int
    {
        if (is_null($this->totalEntries)) {
            throw new \BadMethodCallException("Total number of entries must be set using method \"setTotal()\" before call of the method \"totalEntries()\"");
        }
        
        return $this->totalEntries;
    }
    
    public function total() : int
    {
        if (is_null($this->totalEntries)) {
            throw new \BadMethodCallException("Total number of entries must be set using method \"setTotal()\" before call of the method \"total()\"");
        }
        
        return ceil($this->totalEntries() / $this->perPage());
    }
    
    public function link(array $vars=[]) : string
    {
        $vars = array_merge($this->vars, $vars);

        foreach ($vars as $i=>$var) { // unset nullified vars
            if (is_null($var)) {
                unset($vars[$i]);
            }
        }

        $link = empty($vars) ? $this->path : $this->path. "?" . http_build_query($vars);

        return $link;
    }
}
