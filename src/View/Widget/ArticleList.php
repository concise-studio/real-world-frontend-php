<?php if (empty($articles)) { ?>
    <p>No articles are here... yet.</p>
<?php } else { ?>
    <?php foreach ($articles as $article) { ?>                    
        <div class="article-preview">
            <div class="article-meta">
                <a href="/profile/<?= $article->author->username ?>">
                    <img src="<?= $article->author->image ?>">
                </a>
                <div class="info">
                    <a href="/profile/<?= $article->author->username ?>" class="author">
                        <?= $article->author->username ?>
                    </a>
                    <span class="date"><?= date("F j, Y", strtotime($article->createdAt)) ?></span>
                </div>                
                <?php if (!$article->favorited) { ?>
                    <a href="/blog/favorite-article/<?= $article->slug ?>" class="btn btn-sm btn-outline-primary">
                        <i class="ion-heart"></i> <?= $article->favoritesCount ?>
                    </a>
                <?php } else { ?>
                    <a href="/blog/unfavorite-article/<?= $article->slug ?>" class="btn btn-sm btn-primary">
                        <i class="ion-heart"></i> <?= $article->favoritesCount ?>
                    </a>
                <?php } ?>
            </div>
            <a href="/article/<?= $article->slug ?>" class="preview-link">                            
                <h1><?= $article->title ?></h1>
                <p><?= $article->description ?></p>
                <span>Read more...</span>
                
                <?php if (!empty($article->tagList)) { ?>
                    <ul class="tag-list">
                        <?php foreach ($article->tagList as $tag) { ?>
                            <li class="tag-default tag-pill tag-outline">
                                <?= $tag ?>
                            </li>
                        <?php } ?>
                    </ul>
                <?php } ?>
            </a>
        </div>
    <?php } ?>
<?php } ?>

<?= $this->widget("Pagination", $articlesPagination) ?>
