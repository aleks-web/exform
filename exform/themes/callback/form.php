<?php

require_once(__DIR__ . '../../../core/bootstrap.php');
global $config;

// asdasd

$curentTheme = ExformTheme::getCurrentTheme();
$themeName = $curentTheme->getName();
$configTheme = $curentTheme->getConfig();
$isYaCaptha = (bool)$configTheme['ya_captha'];
$isModal = (bool)$configTheme['is_modal'];
$zIndex = (int) $config['request_data']['z_index'] + 10;
$formHeaderText = $configTheme['form_header_text'];

?>

<div class="exform-wrapper <?= $curentTheme->name ?> <?= $isModal ? 'is_modal' : 'is_not_modal' ?>" style="z-index: <?= $zIndex ?>;">
    <div class="exform-btn-close" onclick="window.Exform.closeAllModals();">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M 7.71875 6.28125 L 6.28125 7.71875 L 23.5625 25 L 6.28125 42.28125 L 7.71875 43.71875 L 25 26.4375 L 42.28125 43.71875 L 43.71875 42.28125 L 26.4375 25 L 43.71875 7.71875 L 42.28125 6.28125 L 25 23.5625 Z"></path></svg>
    </div>

    <div class="exform-header"><?= $formHeaderText ?></div>

    <form name="exform">

        <div class="exform-fields">
            <div class="exform-input">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1" />
                </svg>
                <input type="text" name="ex_username" placeholder="Ваше имя" autocomplete="given-name">
            </div>

            <div class="exform-input">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                    <path d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z" />
                </svg>
                <input type="text" name="ex_phone" placeholder="Ваш телефон" autocomplete="tel">
            </div>

            <div class="exform-input">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16">
                    <path d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2zm-2 9.8V4.698l5.803 3.546zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.5 4.5 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586zM16 9.671V4.697l-5.803 3.546.338.208A4.5 4.5 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671" />
                    <path d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791" />
                </svg>
                <input type="text" name="ex_email" placeholder="Ваш email" autocomplete="email">
            </div>

            <div class="exform-textarea">
                <textarea name="ex_message" rows="3" cols="30" placeholder="Ваше сообщение"></textarea>
            </div>
        </div>

        <button type="submit">Отправить заявку</button>

        <input type="hidden" name="theme" value="<?= $themeName; ?>">
	</form>

</div>