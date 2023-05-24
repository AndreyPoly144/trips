<?php

//подключение компосера
require($_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php');
//подключение моего класса
require('classes/App.php');
$obj = new classes\App();
//установить текущий путь на яндекс диске в $_SESSION
classes\App::setSessionCurPath();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/style.css" rel="stylesheet">
    <title>CRUD App</title>
</head>
<body>
<?php


//вывести все папки и файлы
echo '<div class="items">';
$obj->showItems();
echo '</div>';
//вывести скрытый хайдбар
$obj->hideBar();
//кнопка для загрузки файлов
$obj->loadItem();
//показать текущий путь на яндекс диске
classes\App::showCurrentPath();

?>

<script src="js/log.js"></script>
</body>
</html>



