<ul class="nav navbar-nav pull-xs-right">
    <?php foreach ($items as $item) { ?>
        <li class="nav-item">
            <a 
                class="nav-link <?= $item->isActive ? "active" : "" ?>" 
                href="<?= $item->link ?>"
            >
                <?= $item->icon ?>
                <?= $item->title ?>
            </a>
        </li>
    <?php } ?>
</ul>
