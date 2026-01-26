<?php
header('Content-Type: application/json; charset=utf-8');

require_once(realpath(__DIR__ . '../../core/bootstrap.php'));

$GLOBALS['config']['z_index'] = $_POST['z_index'];

ExformTheme::setCurrentThemeByName($_POST['theme']);
$theme = ExformTheme::getCurrentTheme();
$config = $theme->getConfig();

$to = $config['to_mail'];
$subject = 'Отправка письма';
$headers = 	"From: " . $config['from_mail'] . "\r\n" .
	'X-Mailer: PHP/' . phpversion() . "\r\n" .
	"MIME-Version: 1.0\r\n" .
	"Content-Type: text/html; charset=utf-8\r\n" .
	"Content-Transfer-Encoding: 8bit\r\n\r\n";

// $mailStatus = mail($to, "=?utf-8?B?" . base64_encode($subject) . "?=", 'test', $headers, '-f' . $config['from_mail']);

$mailStatus = false;

if ($mailStatus) {
    echo jsonResponse($config['send_form_success_msg'], true, ['post' => $_POST, 'msg' => $theme->requireMsg()]);
} else {
    echo jsonResponse($config['send_form_error_msg'], false, ['post' => $_POST, 'msg' => $theme->requireMsg(false)]);
}