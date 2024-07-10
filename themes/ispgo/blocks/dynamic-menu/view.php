<?php
$menuItems = [
    ['title' => $block->setting('menu_item_1_title'), 'url' => $block->setting('menu_item_1_url')],
    ['title' => $block->setting('menu_item_2_title'), 'url' => $block->setting('menu_item_2_url')],
    ['title' => $block->setting('menu_item_3_title'), 'url' => $block->setting('menu_item_3_url')],
    ['title' => $block->setting('menu_item_4_title'), 'url' => $block->setting('menu_item_4_url')],
    ['title' => $block->setting('menu_item_5_title'), 'url' => $block->setting('menu_item_5_url')],
]; ?>


<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <?php foreach ($menuItems as $menuItem) : ?>
                    <?php if (!empty($menuItem['title']) && !empty($menuItem['url'])) : ?>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page"
                               href="<?= $menuItem['url']; ?>"><?= $menuItem['title']; ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</nav>
