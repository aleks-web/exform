<?php
    header('Content-Type: application/json; charset=utf-8');

    require_once(realpath(__DIR__ . '../../../core/bootstrap.php'));
    
    $jsonDataDecode = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($jsonDataDecode['content'])) {
        echo jsonResponse('Отсутствует content в теле запроса', false);
    }

    $file = realpath(EXFORM_PATH . '/config.ini');
    if (file_exists($file)) {
        $result = file_put_contents($file, $jsonDataDecode['content']);
        echo jsonResponse('Успешное сохранение файла', true, $jsonDataDecode);   
    }
?>