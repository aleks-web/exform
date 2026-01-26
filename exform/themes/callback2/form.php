<?php

require_once(__DIR__ . '../../../core/bootstrap.php');
global $config;

$curentTheme = ExformTheme::getCurrentTheme();
$themeName = $curentTheme->getName();
$configTheme = $curentTheme->getConfig();
$isYaCaptha = (bool)$configTheme['ya_captha'];
$isModal = (bool)$configTheme['is_modal'];
$zIndex = (int) $config['request_data']['z_index'] + 10;

?>

<div class="exform-wrapper <?= $curentTheme->name ?> <?= $isModal ? 'is_modal' : 'is_not_modal' ?>" style="z-index: <?= $zIndex ?>;">
    <div class="exform-btn-close" onclick="window.exform.closeAllModals();"></div>

    <div class="exform-header">Свяжитесь с нами asdasdasd</div>

    <form name="exform">
        <input type="text" name="username" placeholder="Ваше имя">
        <button type="submit">Отправить заявку</button>

        <input type="hidden" name="theme" value="<?= $themeName; ?>">
	</form>

</div>