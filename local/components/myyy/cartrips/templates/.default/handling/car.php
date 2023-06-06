<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

//добавляем поездку
\Bitrix\Main\Loader::includeModule('iblock');
$el = new CIBlockElement();
$PROP[64] = date('d.m.Y H:i:s', strtotime($_POST['start']));
$PROP[65] = date('d.m.Y H:i:s', strtotime($_POST['end']));
$PROP[66] = $_POST['carid'];
$arLoadProductArray = [
    'IBLOCK_ID' => 20,
    'PROPERTY_VALUES' => $PROP,
    'NAME' => 'Поездка по апи',
    'ACTIVE' => 'Y',
];
if ($tripId = $el->Add($arLoadProductArray)) {        //если поездка успешно добавлена, то добавим id поездки в соотв машину
    $res = CIBlockElement::GetProperty(14, $_POST['carid'], "sort", "asc", ['CODE' => 'TRIPSID']);
    while ($ob = $res->GetNext()) {
        $oldTripsId[] = $ob['VALUE'];
    }
    CIBlockElement::SetPropertyValuesEx($_POST['carid'], false, [67 => array_merge($oldTripsId, [$tripId])]);
    $result = [
        'status' => 'success',
        'message' => 'Вы успешно забранировали машину'
    ];
    header('Content-type: application/json');
    echo json_encode($result);
    exit;
} else {
    $result = [
        'status' => 'error',
        'message' => 'Произошла ошибка, машина не забранирована'
    ];
    header('Content-type: application/json');
    echo json_encode($result);
    exit;
}



