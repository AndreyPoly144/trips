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

        $cars = $_SESSION['cars'];
        foreach ($cars as $carid => $car) {
            if (!empty($car['trips'])) {
                foreach ($car['trips'] as $tripid => $trip) {
                    $carStart = strtotime($trip['start']);
                    $carEnd = strtotime($trip['end']);
                    if ($start < $carStart && $end > $carStart) {
                        unset($cars[$carid]);
                        continue;
                    }
                    if ($start < $carEnd && $end > $carEnd) {
                        unset($cars[$carid]);
                        continue;
                    }
                    if ($start >= $carStart && $end <= $carEnd) {
                        unset($cars[$carid]);
                        continue;
                    }
                    if ($start <= $carStart && $end >= $carEnd) {
                        unset($cars[$carid]);
                    }
                }
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
        echo '<p>Кликните по свободному автомобилю, чтобы забронировать его на указанное время</p>';
        foreach ($cars as $car) {
            echo "<p class='car' data-carid='{$car['ID']}'>{$car['NAME']} -{$car['PROPERTY_MODELID_PROPERTY_MODELNAME_VALUE']} - {$car['categoryName']} - {$car['PROPERTY_DRIVERID_PROPERTY_DRIVERNAME_VALUE']}</p>";
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
