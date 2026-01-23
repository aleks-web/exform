<?php
header('Content-Type: application/json; charset=utf-8');

require_once(realpath(__DIR__ . '../../core/bootstrap.php'));
global $config;
$jsonDataDecode = json_decode(file_get_contents('php://input'), true);

$requestContentType = getallheaders()['Content-Type'];

if (!isset($requestContentType) || $requestContentType !== 'application/json') {
    http_response_code(400);
    echo jsonResponse('Не установлен заголовок Content-Type со значением application/json', false);
}

if (!isset($jsonDataDecode['action']) || !isset($jsonDataDecode['theme'])) {
    http_response_code(400);
    echo jsonResponse('Не заданы action и theme в теле запроса', false);
}

$config['request_data'] = $jsonDataDecode;

ExformTheme::setCurrentThemeByName($jsonDataDecode['theme']);
echo jsonResponse('Успешный рендер формы', true, ['form' => ExformTheme::getCurrentTheme()->requireForm()]);