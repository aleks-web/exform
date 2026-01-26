<?php
header('Content-Type: application/json; charset=utf-8');

require_once(realpath(__DIR__ . '../../core/bootstrap.php'));

global $config;

$response = [];
$response['config'] = $config;

$response['themes'] = ExformTheme::getAllThemesArray();

echo json_encode($response, JSON_UNESCAPED_SLASHES);