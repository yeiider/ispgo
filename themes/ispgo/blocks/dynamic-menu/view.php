<?php
$menuItems = [
    ['title' => $block->setting('menu_item_1_title'), 'url' => $block->setting('menu_item_1_url')],
    ['title' => $block->setting('menu_item_2_title'), 'url' => $block->setting('menu_item_2_url')],
    ['title' => $block->setting('menu_item_3_title'), 'url' => $block->setting('menu_item_3_url')],
    ['title' => $block->setting('menu_item_4_title'), 'url' => $block->setting('menu_item_4_url')],
    ['title' => $block->setting('menu_item_5_title'), 'url' => $block->setting('menu_item_5_url')],
];?>

<nav class="dynamic-menu">
    <ul>
        <h1>menu</h1>
        <?php foreach ($menuItems as $menuItem) : ?>
            <?php if (!empty($menuItem['title']) && !empty($menuItem['url'])) : ?>
                <li><a href="<?php echo $menuItem['url']; ?>"><?php echo $menuItem['title']; ?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</nav>

<style>
    .dynamic-menu {
        /* Estilos personalizados para el menú */
    }
    .dynamic-menu ul {
        list-style-type: none;
    }
    .dynamic-menu li {
        display: inline;
        margin-right: 10px;
    }
</style>
