<?php 
$map = [
    '/'                                 => ["Main", "mainPage"],
    '/login'                            => ["Auth", "loginPage"],
    '/register'                         => ["Auth", "registerPage"],
    '/settings'                         => ["Profile", "settingsPage"],
    '/editor'                           => ["Blog", "createArticlePage"],
    '/editor/:articleSlug'              => ["Blog", "editArticlePage"],
    '/article/:articleSlug'             => ["Blog", "viewArticlePage"],
    '/profile/:username'                => ["Profile", "viewProfilePage"],
    '/profile/:username/favorites'      => ["Profile", "viewFavoritesPage"],
    
    '/do-registration'                  => ["Auth", "doRegistration"],
    '/do-login'                         => ["Auth", "doLogin"],
    '/blog/delete-article'              => ["Blog", "deleteArticle"],
    '/blog/add-comment-to-article'      => ["Blog", "addCommentToArticle"],
    '/blog/delete-comment-from-article' => ["Blog", "deleteCommentFromArticle"],
    '/profile/save-settings'            => ["Profile", "saveSettings"]
];

$map = array_map(function($executable) { 
    return ["\RealWorldFrontendPhp\Controller\\{$executable[0]}", $executable[1]];
}, $map);

return $map;