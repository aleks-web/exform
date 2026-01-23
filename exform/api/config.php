<?php
header('Content-Type: application/json; charset=utf-8');

require_once(realpath(__DIR__ . '../../core/bootstrap.php'));

global $config;

$response = $config;

if (isset($config['exform_urn'])) {
    $response['exform_urn'] = rtrim($config['exform_urn'], '/');
}
$response['themes'] = ExformTheme::getAllThemesArray();

echo json_encode($response, JSON_UNESCAPED_SLASHES);