<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class testList extends CBitrixComponent
{
    public function executeComponent(): void
    {
        //получаем должность текущего пользователя
        $positionId = self::getPositionId();

        //получаем категории камфорта, которые соответсвуют должности текущего пользователя
        $arrCategoryId = self::getCategoryId($positionId);
        $arrCategoryNames = [];       //в этот массив будем класть названия катигорий для соответвующих моделей

        //получаем модели машин которые, соответсвуют этим категориям камфорта
        $arrCarModels = self::getCarModels($arrCategoryId, $arrCategoryNames);

        //получаем список машин, которые соответсвуют этим моделям
        $arrCarList = self::getCarList($arrCarModels, $arrCategoryNames);

        $this->arResult = $arrCarList;
        $this->includeComponentTemplate();
    }

    //возвращает ID должности текущего пользователя(сотрудника), в битриксе я создал  у пользователей доп. поле, в котром указана их текущая должность
    public static function getPositionId(): int
    {
        global $USER;
        $cur_user_id = $USER->GetID();
        $rsUser = CUser::GetByID($cur_user_id);
        $arUser = $rsUser->Fetch();
        return $arUser['UF_POSITION1'];
    }

    //возвращает массив ID категорий по соответсвующей должности
    public static function getCategoryId(int $elemId): array
    {
        //получаем ид инфоблока данной должности
        $Res = CIBlockElement::GetByID($elemId);
        $arItem = $Res->GetNext();

        //получаем св-ва элемента
        $arrCategoryId = [];
        $res = CIBlockElement::GetProperty($arItem['IBLOCK_ID'], $elemId, [], ['CODE' => 'CATEGID']);
        while ($ob = $res->GetNext()) {
            $arrCategoryId[] = $ob['VALUE'];
        }
        return $arrCategoryId;
    }

    //возвращает массив моделей машин по соответсвующим категориям
    public static function getCarModels(array $arrCategoryId, array &$arrCategoryNames): array
    {
        $arrCarModels = [];

        $res = CIBlockElement::GetList(
            [],
            ['PROPERTY_CATEGORYID' => $arrCategoryId],
            false,
            [],
            ['ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_MODELNAME', 'PROPERTY_CATEGORYID.PROPERTY_CATEGORYNAME']
        );
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arrCarModels[$arFields['ID']] = $arFields['ID'];
            $arrCategoryNames[$arFields['ID']] = $arFields['PROPERTY_CATEGORYID_PROPERTY_CATEGORYNAME_VALUE'];
        }
        return $arrCarModels;
    }

    //возвращает список машин
    public static function getCarList(array $arrCarModels, array $arrCategoryNames): array
    {
        $arrCarList = [];
        $res = CIBlockElement::GetList(
            [],
            ['PROPERTY_MODELID' => $arrCarModels],
            false,
            [],
            ['ID', 'NAME', 'IBLOCK_ID', 'PROPERTY_MODELID', 'PROPERTY_DRIVERID', 'PROPERTY_MODELID.PROPERTY_MODELNAME', 'PROPERTY_MODELID.PROPERTY_CATEGORYID', 'PROPERTY_DRIVERID.PROPERTY_DRIVERNAME', 'PROPERTY_TRIPSID', 'PROPERTY_TRIPSID.PROPERTY_STARTTIME', 'PROPERTY_TRIPSID.PROPERTY_ENDTIME']
        );
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            if (empty($arrCarList[$arFields['ID']])) {
                $arrCarList[$arFields['ID']] = $arFields;
            }
            if (!empty($arFields['PROPERTY_TRIPSID_VALUE'])) {
                $arrCarList[$arFields['ID']]['trips'][$arFields['PROPERTY_TRIPSID_VALUE']] = ['start' => $arFields['PROPERTY_TRIPSID_PROPERTY_STARTTIME_VALUE'], 'end' => $arFields['PROPERTY_TRIPSID_PROPERTY_ENDTIME_VALUE']];
            }
        }

        //заносим в список машин название категории для каждой машины
        foreach ($arrCarList as &$car) {
            foreach ($arrCategoryNames as $model => $categoryName) {
                if ($car['PROPERTY_MODELID_VALUE'] == $model) {
                    $car['categoryName'] = $categoryName;
                }
            }
        }
        unset($car);
        return $arrCarList;
    }
}