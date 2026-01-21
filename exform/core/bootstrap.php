<?php

error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

global $config;
$config = parse_ini_file(realpath(__DIR__ . '/../config.ini'));

require_once(__DIR__ . '/const.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/autoload.php');


foreach (get_all_theme_folders() as $themeName => $themePath) {
    $theme = new ExformTheme($themeName, $themePath);
    $config['themes'][] = $theme;
}

echo '<pre>';
print_r($config);
echo '</pre>';