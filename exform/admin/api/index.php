<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(realpath(__DIR__ . '../../../core/bootstrap.php'));

    $themes = ExformTheme::getAllThemes();


    $tm = [];
    foreach ($themes as $k => $theme) {
        $tm[$k] = $theme->toArray();
        $tm[$k]['files']['form'] = $theme->getContentFromFile('form.php');
        $tm[$k]['files']['style'] = $theme->getContentFromFile('/assets/style.css');
        $tm[$k]['files']['success_msg'] = $theme->getContentFromFile('success_msg.php');
        $tm[$k]['files']['error_msg'] = $theme->getContentFromFile('error_msg.php');
        $tm[$k]['files']['config'] = $theme->getContentFromFile('config.ini');
    }

    echo jsonResponse('Успех', true, $tm);
?>