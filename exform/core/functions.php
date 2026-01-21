<?php


function get_all_theme_folders() {
    $items = scandir(THEMES_PATH);

    $folders = [];
    foreach ($items as $item) {
        if($item !== '..' && $item !== '.') {
            $folders[] = $item;
        }
    }

    $paths = [];
    foreach ($folders as $folder) {
        $paths[$folder] = realpath(THEMES_PATH . '/' . $folder);
    }

    return $paths;
}

function dd($dd) {
    echo '<pre>';
    print_r($dd);
    echo '</pre><br><br><hr><br><br>';
}