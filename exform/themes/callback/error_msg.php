<?php

require_once(__DIR__ . '../../../core/bootstrap.php');

$theme = ExformTheme::getCurrentTheme();
$config = $theme->getConfig();

?>

<div class="exform-wrapper is_modal exform-msg exform-msg__error" style="z-index: <?= $config['z_index'] + 10; ?>;">
    <div><?php echo $config['send_form_error_msg']; ?></div>
    <button onclick="window.exform.closeAllModals()">Хорошо!</button>
</div>