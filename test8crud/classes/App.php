<?php

namespace classes;

class App
{
    public const  TOKEN = 'y0_AgAAAAAE8Kz-AAnyNAAAAADjySJNvUavgCXcQqWVw6UmwlWMPk6BFEY';
    public object $disk;

    public function __construct()
    {
        $this->disk = new \Arhitector\Yandex\Disk(self::TOKEN);
    }

    //выводит папки и файлы по переданому $path
    public function showItems($path = 'disk:/'): void
    {
        $items = $this->disk->getResource($path, 300)->items->toArray();

        foreach ($items as $item) {
            if ($item->isDir()) {
                echo '<div class="item" data-pathtoitem="' . $item->getIterator()['path'] . '" data-item="true" data-dir="true"><img src="images\dir.png" alt="dir" class="dirpic"  data-item="true" data-dir="true" style="width: 75px;"><p  data-item="true" data-dir="true">';
                echo $item->getIterator()['name'];
                echo '</p></div>';
            } elseif ($item->isFile()) {
                echo '<div class="item" data-pathtoitem="' . $item->getIterator()['path'] . '" data-item="true"><img src="images\file1.png" alt="file" class="filepic"  data-item="true"  style="width: 75px;"><p  data-item="true">';
                echo $item->getIterator()['name'];
                echo '</p></div>';
            }
        }

    }

    //выводит html для хайдбара
    public function hideBar()
    {
        ?>
        <div class="hidebar hidden">
            <p class="hbtext">Удалить</p>
        </div>

        <?php
    }

    //удаляет папку/файл по переданому пути
    public function deleteItem($path)
    {
        return $this->disk->getResource($path)->delete();

    }

    //выводит html кнопки загрузки
    public function loadItem()
    {
        echo "<input type='file' name='filename' hidden='hidden'> ";
        echo "<div class='btndiv'><button class='button'>Загрузить</button><p class='text'></p></div>";
    }

    //загружает файл на яндекс диск
    public function uploadFile($serverPath, $yandexPath)
    {
        return $this->disk->getResource($yandexPath)->upload($serverPath);
    }

    //показывает текущий путь где находится пол-ль
    public static function showCurrentPath()
    {
        echo "<div class='curpath'><img class='back hidden' src='images\back.png' alt='back'  ><div class='curpathtext'>{$_SESSION['curpath']}</div></div>";
    }

    //устанавливаем в сессию путь
    public static function setSessionCurPath($path = 'disk:/')
    {
        session_start();
        $_SESSION['curpath'] = $path;
    }
}