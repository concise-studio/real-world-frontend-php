<?php 
$map = [
    '/do-registration'                      => ["Auth", "doRegistration"],
    '/do-login'                             => ["Auth", "doLogin"],
    '/do-logout'                            => ["Auth", "doLogout"],
    '/blog/publish-article'                 => ["Blog", "publishArticle"],
    '/blog/favorite-article/:articleSlug'   => ["Blog", "favoriteArticle"],
    '/blog/unfavorite-article/:articleSlug' => ["Blog", "unfavoriteArticle"],
    '/blog/delete-article/:articleSlug'     => ["Blog", "deleteArticle"],
    '/blog/add-comment-to-article'          => ["Blog", "addCommentToArticle"],
    '/blog/delete-comment-from-article'     => ["Blog", "deleteCommentFromArticle"],
    '/profile/save-settings'                => ["Profile", "saveSettings"],
    
    '/'                                     => ["Main", "mainPage"],
    '/login'                                => ["Auth", "loginPage"],
    '/register'                             => ["Auth", "registerPage"],
    '/settings'                             => ["Profile", "settingsPage"],
    '/editor'                               => ["Blog", "createArticlePage"],
    '/editor/:articleSlug'                  => ["Blog", "editArticlePage"],
    '/article/:articleSlug'                 => ["Blog", "viewArticlePage"],
    '/profile/:username'                    => ["Profile", "viewProfilePage"],
    '/profile/:username/favorites'          => ["Profile", "viewFavoritesPage"]
];

$map = array_map(function($executable) { 
    return ["\RealWorldFrontendPhp\Controller\\{$executable[0]}", $executable[1]];
}, $map);

return $map;
