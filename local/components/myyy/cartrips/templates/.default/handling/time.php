<?php
session_start();
//обработка времени
if ($_GET['car'] == 'list') {         //если нажали на кнопку
    if ($_GET['start'] == '' || $_GET['end'] == '') {
        $result = [
            'status' => 'error',
            'message' => 'Укажите время начала и окончания поездки'
        ];
        header('Content-type: application/json');
        echo json_encode($result);
        exit;
    } else {
        $start = strtotime($_GET['start']);
        $end = strtotime($_GET['end']);

        if ($start <= time() || $end <= time()) {
            $result = [
                'status' => 'error',
                'message' => 'Укажите время позднее текущего'
            ];
            header('Content-type: application/json');
            echo json_encode($result);
            exit;
        }

        if ($end <= $start) {
            $result = [
                'status' => 'error',
                'message' => 'Окончание поездки должно быть позднее начала'
            ];
            header('Content-type: application/json');
            echo json_encode($result);
            exit;
        }

        //если машина находится в поездке и время поездки заходит в диапозон от $start до $end, то такую машину удаляем из списка
        $cars = $_SESSION['cars'];
        foreach ($cars as $carid => $car) {
            $carStart = strtotime($car['PROPERTY_STARTTIME_VALUE']);
            $carEnd = strtotime($car['PROPERTY_ENDTIME_VALUE']);
            if ($carStart > $start && $carStart < $end) {
                unset($cars[$carid]);
                continue;
            }
            if ($carEnd > $start && $carEnd < $end) {
                unset($cars[$carid]);
                continue;
            }
        }

        if (!$cars) {
            $result = [
                'status' => 'error',
                'message' => 'Свободных машин нет'
            ];
            header('Content-type: application/json');
            echo json_encode($result);
            exit;
        }

        //кладем в буфер вывод всех свободных автомобилей и отправляет в js ответ
        ob_start();
        echo '<p>Свободные автомобили по указаному времени</p>';
        foreach ($cars as $car) {
            echo "<p>{$car['NAME']} -{$car['PROPERTY_MODELID_PROPERTY_MODELNAME_VALUE']} - {$car['categoryName']} - {$car['PROPERTY_DRIVERID_PROPERTY_DRIVERNAME_VALUE']}</p>";
        }
        $output = ob_get_contents();
        ob_end_clean();

        $result = [
            'status' => 'success',
            'carlist' => $output
        ];
        header('Content-type: application/json');
        echo json_encode($result);
        exit;
    }
}
