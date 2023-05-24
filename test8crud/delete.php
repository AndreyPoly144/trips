<?php
//подключение компосера
require($_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php');
//подключение моего класса
require('classes/App.php');

//путь к папке/файлу, на который нажали удалить
$path = $_POST['data-pathtoitem'];

$obj = new classes\App();
//удалить папку/файл
$delete = $obj->deleteItem($path);
//отправляем результат в log.js
header('Content-type: application/json');
echo json_encode($path);
exit;
