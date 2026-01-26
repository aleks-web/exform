<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(realpath(__DIR__ . '../../../core/bootstrap.php'));

    $themes = ExformTheme::getAllThemes();


    $tm = [];
    foreach ($themes as $k => $theme) {
        $tm[$k] = $theme->toArray();
        $tm[$k]['files']['form'] = $theme->getContentFromFile('form.php');
        $tm[$k]['files']['style'] = $theme->getContentFromFile('/assets/style.css');
    }


    echo jsonResponse('Успех', true, $tm);
?>