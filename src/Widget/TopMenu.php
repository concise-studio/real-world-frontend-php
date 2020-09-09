<?php

namespace RealWorldFrontendPhp\Widget;

use \RealWorldFrontendPhp\Core\Router as Router;
use \RealWorldFrontendPhp\Core\GlobalWidget as CoreGlobalWidget;

class TopMenu extends CoreGlobalWidget
{
    public function __invoke()
    {
        $items = $this->getItems();
        
        return $this->view->render("TopMenu", compact("items"));
    }
    
    protected function getItems()
    {
        $user = $this->session->getUser();
        $items = $user->isGuest() ? $this->getGuestItems() : $this->getAuthorizedItems();
        $items = $this->setActiveItem($items);
    
        return $items;
    }
    
    protected function getGuestItems()
    {
        return [
            (object)['link'=>"/", 'title'=>"Home", 'icon'=>null, 'isActive'=>false],
            (object)['link'=>"/login", 'title'=>"Sign in", 'icon'=>null, 'isActive'=>false],
            (object)['link'=>"/register", 'title'=>"Sign up", 'icon'=>null, 'isActive'=>false]
        ];
    }
    
    protected function getAuthorizedItems()
    {
        $user = $this->session->getUser();
        $username = $user->getUsername();
        $avatar = $user->getAvatar();
        $profileLink = "/profile/{$username}"; 
        
        return [
            (object)['link'=>"/", 'title'=>"Home", 'icon'=>null, 'isActive'=>false],
            (object)['link'=>"/editor", 'title'=>"New Post", 'icon'=>"<i class='ion-compose'></i>&nbsp;", 'isActive'=>false],
            (object)['link'=>"/settings", 'title'=>"Settings", 'icon'=>"<i class='ion-gear-a'></i>&nbsp;", 'isActive'=>false],
            (object)['link'=>$profileLink, 'title'=>$username, 'icon'=>"<img class='user-pic' src='{$avatar}'>&nbsp;", 'isActive'=>false]
        ];
    }
    
    protected function setActiveItem(array $items)
    {
        $uri = $this->request->getUri();
        $links = array_column($items, "link");
        $activeLink = Router::defineRoute($uri, $links);
        
        foreach ($items as $item) {
            if ($item->link === $activeLink) {
                $item->isActive = true;
                break;
            }
        }
        
        return $items;
    }
}
