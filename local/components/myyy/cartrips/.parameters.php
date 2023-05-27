<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

//if(!CModule::IncludeModule("iblock"))
//    return;

//получаем все ид инфоблоков для выбраного типа инфоблока
//$arIBlocks=["-"=>" "];
//$db_iblock = CIBlock::GetList(["SORT"=>"ASC"], ["SITE_ID"=>$_REQUEST["site"],
//    "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")]);
//while($arRes = $db_iblock->Fetch())
//    $arIBlocks[$arRes["ID"]] = "[".$arRes["ID"]."] ".$arRes["NAME"];
$iblockType=16;   //тип инфоблока в котором находятся все нужные для задания инфоблоки

$arComponentParameters=[
    "PARAMETERS" => [
        "IBLOCK_TYPE" => [
            "PARENT" => "BASE",
            "NAME" => GetMessage("TEST_IBLOCK_LIST_TYPE"),
            "TYPE" => "STRING",
            "VALUES" => $iblockType,
        ],
    ]
];
