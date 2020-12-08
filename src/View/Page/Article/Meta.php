<div class="article-meta">
    <a href="/profile/<?= $article->author->username ?>">
        <img src="<?= $article->author->image ?>">
    </a>
    <div class="info">
        <a href="/profile/<?= $article->author->username ?>" class="author">
            <?= $article->author->username ?>
        </a>
        <span class="date">
            <?= date("F jS", strtotime($article->createdAt)) ?>
        </span>
    </div>

    <?php if ($isAuthor) { ?>
        <a href="/editor/<?= $article->slug ?>" class="btn btn-sm btn-outline-secondary">
            <i class="ion-edit"></i>
            &nbsp;
            Edit Artcile
        </a>
        &nbsp;
        <a 
            href="/blog/delete-article/<?= $article->slug ?>?redirectTo=<?= urlencode("/profile/{$article->author->username}") ?>" 
            class="btn btn-sm btn-outline-danger"
        >
            <i class="ion-trash-a"></i>
            &nbsp;
            Delete Article
        </a>       
    <?php } else { ?>
        <button class="btn btn-sm btn-outline-secondary">
            <i class="ion-plus-round"></i>
            &nbsp;
            Follow <?= $article->author->username ?>
        </button>
        &nbsp;
        <button class="btn btn-sm btn-outline-primary">
            <i class="ion-heart"></i>
            &nbsp;
            Favorite Post <span class="counter">(<?= $article->favoritesCount ?>)</span>
        </button>
    <?php } ?>
</div>
