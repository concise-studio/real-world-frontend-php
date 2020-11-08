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

                <?= $this->render("Widget/ArticleList", compact("articles", "articlesPagination")) ?>
            </div>
        </div>
    </div>
</div>
