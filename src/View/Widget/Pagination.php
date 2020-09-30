<?php if ($pagination->total() > 1) { ?>
    <ul class="pagintaion">
        <li class="page-item" <?= $pagination->current() > 1 ? "" : "style='display:none'" ?>>
            <a class="page-link" href="<?= $pagination->link(['current'=>$pagination->prev()]) ?>">
                &laquo; Prev
            </a>
        </li>
        <li class="page-item <?= $pagination->current() == 1 ? "active" : "" ?>">
            <a class="page-link" href="<?= $pagination->link(['current'=>1]) ?>">
                1
            </a>
        </li>
        
        <li class="page-item" <?= $pagination->current() > 4 ? "" : "style='display:none'" ?>>
            <a class="page-link">...</a>
        </li>                    
        
        <?php for ($page = ($pagination->current()-2); $page <= ($pagination->current()+2); $page++) { ?>
            <?php if ($page > 1 && $page < $pagination->total()) { ?>
                <li class="page-item <?= $page == $pagination->current() ? "active" : ""?>">
                    <a class="page-link" href="<?= $pagination->link(['current'=>$page]) ?>">
                        <?= $page ?>
                    </a>
                </li>
            <?php } ?>
        <?php } ?>
        
        <li class="page-item" <?= $pagination->current() < $pagination->total()-2 ? "" : "style='display:none'" ?>>
            <a class="page-link">...</a>
        </li>
        
        <li class="page-item <?= $pagination->total() == $pagination->current() ? "active" : ""?>" <?= $pagination->total() < 5 ? "style='display:none'" : "" ?>>
            <a class="page-link" href="<?= $pagination->link(['current'=>$pagination->total()]) ?>">
                <?= $pagination->total() ?>
            </a>                
        </li>
        <li class="page-item" <?= $pagination->total() < 4 || $pagination->total() == $pagination->current() ? "style='display:none'" : "" ?>>
            <a class="page-link" href="<?= $pagination->link(['current'=>$pagination->next()]) ?>">
                Next &raquo;
            </a>
       </li>
    </ul>
<?php } ?>

