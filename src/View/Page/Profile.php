<div class="profile-page">
    <div class="user-info">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 offset-md-1">
                    <img src="<?= $profile->image ?>" class="user-img" />
                    <h4><?= $profile->username ?></h4>
                    <p><?= $profile->bio ?></p>
                    <button class="btn btn-sm btn-outline-secondary action-btn">
                        <i class="ion-plus-round"></i>
                        &nbsp;
                        Follow <?= $profile->username ?>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-10 offset-md-1">
                <div class="articles-toggle">
                    <ul class="nav nav-pills outline-active">
                        <li class="nav-item">
                            <a class="nav-link <?= $articlesFeed === "author" ? "active" : ""?>" href="/profile/<?= $profile->username ?>">
                                My Articles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $articlesFeed === "favorited" ? "active" : ""?>" href="/profile/<?= $profile->username ?>?feed=favorited">
                                Favorited Articles
                            </a>
                        </li>
                    </ul>
                </div>

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
                                <button class="btn btn-outline-primary btn-sm pull-xs-right">
                                    <i class="ion-heart"></i> <?= $article->favoritesCount ?>
                                </button>
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
                    
                    <?= $this->widget("Pagination", $articlesPagination) ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
