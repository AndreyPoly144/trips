<?php
session_start();
//подключение компосера
require($_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php');
//подключение моего класса
require('classes/App.php');


$fileName = $_FILES['thefile']['name'];
$tmpName = $_FILES['thefile']['tmp_name'];
$serverPath = 'files/' . $fileName;        //по этому пути лежат загруженые на сервер файлы
$yandexPath = $_SESSION['curpath'] . $fileName;
move_uploaded_file($tmpName, $serverPath);

$obj = new classes\App();
if ($obj->disk->getResource($yandexPath)->has()) {       //если такой файл уже загружен выведем текст
    $response = [
        'result' => 'error',
        'text' => "Файл $fileName уже загружен",
        'fileName' => $fileName,
        'curpath' => $yandexPath
    ];
    header('Content-type: application/json');
    echo json_encode($response);
    exit;
} else {                                                                        //если такого файла нет, загружаем файл
    $result = $obj->uploadFile($serverPath, $yandexPath);     //true при успешной загрузке файла
    $response = [
        'result' => $result,
        'fileName' => $fileName,
        'curpath' => $yandexPath
    ];
    header('Content-type: application/json');
    echo json_encode($response);
    exit;


}
