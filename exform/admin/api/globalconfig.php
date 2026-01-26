<?php
    header('Content-Type: application/json; charset=utf-8');

    $iniFile = realpath(__DIR__ . '../../../config.ini');
    require_once(realpath(__DIR__ . '../../../core/bootstrap.php'));
    
    $content = '';
    if (file_exists($iniFile)) {
        $content = file_get_contents($iniFile);
    }

    echo jsonResponse('Успех', true, $content);
?>