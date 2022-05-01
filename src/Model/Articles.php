<?php

namespace RealWorldFrontendPhp\Model;

use \RealWorldFrontendPhp\Core\Model as CoreModel;
use \RealWorldFrontendPhp\Core\Pagination as Pagination;

class Articles extends CoreModel
{
    public function prepareConnectionToFindAll(?Pagination $pagination=null, array $filters=[])
    {
        return parent::prepareConnectionToFindAllEntries("articles", $pagination, $filters);
    }
    
    public function prepareConnectionToFindAllPersonal(?Pagination $pagination=null)
    {
        return parent::prepareConnectionToFindAllEntries("articles/feed", $pagination);
    }
    
    public function prepareConnectionToFindOne(string $slug)
    {
        return $this->api->prepareConnection("get", "articles/{$slug}");
    }
    
    public function parseFindAllResponse($response, $count=true)
    {
        list($articles, $total) = parent::parseFindAllEntriesResponse($response, $count);
        $articles = array_map([$this, "maybeSetDefaultAuthorImage"], $articles);
        
        return [$articles, $total];
    }
    
    public function parseFindOneResponse($response)
    {
        $article = $response->article;
        $article = $this->maybeSetDefaultAuthorImage($article);
        
        return $article;
    }
    
    
    
    
    
    public function create(array $articleData) 
    {
        $response = $this->api->execute("post", "articles", [], ['article'=>$articleData]);

        return $response->article;
    }
    
    public function update(array $articleData)
    {
        $slug = $articleData['slug'];
        $response = $this->api->execute("put", "articles/{$slug}", [], ['article'=>$articleData]);
        
        return $response->article;
    }
    
    public function delete(string $slug)
    {
        try {
            $this->api->execute("delete", "articles/{$slug}");
        } catch (\RuntimeException $e) {
            if ($e->getCode() !== 404) { // ignore Error 404, method actually works correct in case of that error code 
                throw $e;
            }
        }
    }
    
    public function favorite(string $slug)
    {
        $this->api->execute("post", "articles/{$slug}/favorite");
    }
    
    public function unfavorite(string $slug)
    {
        $this->api->execute("delete", "articles/{$slug}/favorite");
    }
    
    
    
    
    
    private function maybeSetDefaultAuthorImage($article)
    {
        $defaultAuthorImage = "https://static.productionready.io/images/smiley-cyrus.jpg";
        
        if (empty($article->author->image)) {
            $article->author->image = $defaultAuthorImage;
        }
        
        return $article;
    }
}
