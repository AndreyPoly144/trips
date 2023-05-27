<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$session = \Bitrix\Main\Application::getInstance()->getSession();
if (!$session->has('cars')) {
    $session->set('cars', $arResult);
}
?>

<form>
    <input type='datetime-local' class="start" placeholder='<?= GetMessage('TRIP_START_TIME') ?>' name='start'
           value="">
    <input type='datetime-local' class="end" placeholder='<?= GetMessage('TRIP_END_TIME') ?>' name='end' value="">
    <input type="submit" class="btnshow" id="btn-log" value="<?= GetMessage('SHOW_FREE_CARS') ?>">
</form>
<p class="err"></p>

<div class="tripsblock">
</div>

<script src="script.js"></script>

