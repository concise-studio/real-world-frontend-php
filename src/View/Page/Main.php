<div class="home-page">
    <div class="banner">
        <div class="container">
            <h1 class="logo-font">conduit</h1>
            <p>A place to share your knowledge.</p>
        </div>
    </div>

    <div class="container page">
        <div class="row">
            <div class="col-md-9">
                <div class="feed-toggle">
                    <ul class="nav nav-pills outline-active">
                        <li class="nav-item">
                            <?php if ($user->isGuest()) { ?>
                                <a class="nav-link disabled">Your Feed</a>
                            <?php } else { ?>
                                <a class="nav-link <?= $articlesFeed === "personal" ? "active" : ""?>" href="/?feed=personal">Your Feed</a>
                            <?php }  ?>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $articlesFeed === "global" ? "active" : ""?>" href="/">Global Feed</a>
                        </li>
                    </ul>
                </div>
                
                <?= $this->render("Widget/ArticleList", compact("articles", "articlesPagination")) ?>
            </div>

            <div class="col-md-3">
                <div class="sidebar">
                    <p>Popular Tags</p>

                    <div class="tag-list">
                        <?php foreach ($tags as $tag) { ?>
                            <a 
                                href="<?= $articlesPagination->link(['tag'=>$tag, 'feed'=>null, 'current'=>1]) ?>" 
                                class="tag-pill tag-default"
                            >
                                <?= $tag ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
