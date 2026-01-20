<?php

global $config;
$config = parse_ini_file(realpath(__DIR__ . '/../config.ini'));

spl_autoload_register(function ($class_name) {
    $file_path = str_replace('\\', '/', $class_name) . '.php';

    $classes_folder_file = realpath(__DIR__ . '/classes/' . $file_path);
    $src_folder_file = realpath(__DIR__ . '/src/' . $file_path);

    if (file_exists($src_folder_file)) {
        require $src_folder_file;
    }

    else if (file_exists($classes_folder_file)) {
        require $classes_folder_file;
    }
});

require_once(__DIR__ . '/functions.php');