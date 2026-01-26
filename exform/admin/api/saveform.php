<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(realpath(__DIR__ . '../../../core/bootstrap.php'));

    $themes = ExformTheme::getAllThemes();


    


    echo jsonResponse('Успех', true, $_POST);
?>