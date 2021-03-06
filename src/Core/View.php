<?php
namespace RealWorldFrontendPhp\Core;

class View
{
    protected $viewPath;
    protected $layout;
    protected $vars = [];
    protected $layoutVars = [];
    
    
    
    
    
    public function __construct(
        string $viewPath = __DIR__ . "/../View",
        string $layout = "Default"
    ) {        
        $this->setViewPath($viewPath);
        $this->setLayout($layout);
    }
    
    
    
    
    
    public function render(string $view, array $vars=[], string $viewPath=null, bool $useLayout=false)
    {
        $this->addVars($vars);        
        $viewPath = (is_null($viewPath) ? $this->viewPath : $viewPath);
        $rendered = "";
        
        if ($useLayout) {
            $layoutVars = array_merge($vars, $this->layoutVars);
            $layoutVars['content'] = $this->render($view, $vars, $viewPath);
            $rendered = $this->render("Layout/{$this->layout}", $layoutVars, $viewPath);
        } else {
            try {
                extract($vars);    
                ob_start();
                require "{$viewPath}/{$view}.php";
                $rendered = ob_get_clean();
            } catch (\Throwable $e) {
                ob_clean();
                throw $e;
            }
        }
        
        return $rendered;
    }
    
    public function renderPage($view, array $vars=null, $viewPath=null)
    {
        $view = "Page/{$view}";
        
        return $this->render($view, $vars, $viewPath, $useLayout=true); 
    }
    
    
    
    
    
    public function widget(string $name, $args=null) : string
    {
        $widgetClass = "\RealWorldFrontendPhp\Widget\\{$name}";
        
        if (is_subclass_of($widgetClass, "\RealWorldFrontendPhp\Core\GlobalWidget")) {
            $required = ["request", "session"];

            foreach ($required as $requiredVar) {
                if (!array_key_exists($requiredVar, $this->vars)) {
                    throw new \BadMethodCallException("Var {$requiredVar} must be set to render widget {$name}");
                }
            }
            
            $widget = new $widgetClass($this->vars['request'], $this->vars['session']);
        } else {
            $widget = new $widgetClass();
        }
        
        $content = $widget($args);
        
        return $content;
    }
    
    
    
    
    
    protected function setVars(array $vars) : self
    {
        $this->vars = $vars;
        
        return $this;
    }
    
    public function addVars(array $vars) : self
    {
        $this->vars = array_merge($this->vars, $vars);
        
        return $this;        
    }
    
    public function getVars() : array
    {
        return $this->vars;
    }
    
    
    
    
    
    public function setLayout(string $layout) : self
    {
        $this->layout = $layout;
        
        return $this;
    }
    
    public function setLayoutVars(array $layoutVars) : self
    {
        $this->layoutVars = $layoutVars;
        
        return $this;
    }       
    
    public function setViewPath(string $viewPath) : self
    {
        $this->viewPath = $viewPath;
        
        return $this;
    }
}
