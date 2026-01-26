<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(realpath(__DIR__ . '../../../core/bootstrap.php'));
    
    $jsonDataDecode = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($jsonDataDecode['theme_and_file'])) {
        echo jsonResponse('Отсутствует theme_and_file в теле запроса', false);
    }

    $themeAndFile = explode('_', $jsonDataDecode['theme_and_file']);

    $themes = ExformTheme::getAllThemes();

    $res = '';
    foreach ($themes as $theme) {
        $file = '';

        switch ($themeAndFile[1]) {
            case 'style':
                $file = 'assets/style.css';
                break;
            case 'form':
                $file = 'form.php';
                break;
            case 'config':
                $file = 'config.ini';
                break;
            case 'success-msg':
                $file = 'success_msg.php';
                break;
            case 'error-msg':
                $file = 'error_msg.php';
                break;
        }

        if ($theme->getName() === $themeAndFile[0]) {
            $res = $theme->saveContentInFile($file, $jsonDataDecode['content']);
        }
    }


    echo jsonResponse('Успех', true, $jsonDataDecode);
?>