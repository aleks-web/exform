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

function jsonResponse($msg = 'Не удалось обработать запрос', $success = true, $data = []) {
    return json_encode([
       'message' => $msg,
       'success' => $success,
       'data' => $data 
    ], JSON_UNESCAPED_SLASHES);
}