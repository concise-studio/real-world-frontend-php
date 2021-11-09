<div class="editor-page">
    <div class="container page">
        <div class="row">
            <div class="col-md-10 offset-md-1 col-xs-12">
                <?= $this->widget("ErrorMessages", $errorMessages) ?>
                
                <form method="POST" action="/blog/publish-article">
                    <?php if (!empty($article->slug)) { ?>
                        <input type="hidden" name="slug" value="<?= $article->slug ?>">
                    <?php } ?>
                    
                    <fieldset>
                        <fieldset class="form-group">
                            <input 
                                type="text" 
                                name="title" 
                                class="form-control form-control-lg" 
                                placeholder="Article Title"
                                value="<?= $article->title ?? null ?>"
                                required
                            >
                        </fieldset>
                        <fieldset class="form-group">
                            <input 
                                type="text" 
                                name="description" 
                                class="form-control" 
                                placeholder="What's this article about?"
                                value="<?= $article->description ?? null ?>"
                                required
                            >
                        </fieldset>
                        <fieldset class="form-group">
                            <textarea 
                                name="body" 
                                class="form-control" 
                                rows="8"
                                placeholder="Write your article (in markdown)"
                                required
                            ><?= $article->body ?? null ?></textarea>
                        </fieldset>
                        <fieldset class="form-group">
                            <?php if (empty($article->slug)) { ?>
                                <input 
                                    name="tagList" 
                                    type="text" 
                                    class="form-control" 
                                    placeholder="Enter tags"
                                    value="<?= !empty($article->tagList) ? implode(" ", $article->tagList) : null ?>"
                                    required
                                >
                            <?php } elseif (!empty($article->tagList)) { ?>                             
                                <div class="tag-list">
                                    <?php foreach ($article->tagList as $tag) { ?>
                                        <span class="tag-default tag-pill">
                                            <?= $tag ?>
                                        </span>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </fieldset>
                        <button class="btn btn-lg pull-xs-right btn-primary">
                            Publish Article
                        </button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
