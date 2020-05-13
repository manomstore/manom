<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

global $USER;
if ($USER->IsAdmin()) {
    $iblockId = 6;
    $oCIBlockProperty = new CIBlockProperty;
    $aIblockProperties = array(
        array(
            'IBLOCK_ID' => $iblockId,
            'NAME' => 'Сертификаты',
            'CODE' => 'CERTIFICATES',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'MULTIPLE' => 'Y',
            'SORT' => 100,
            'ITEMS' => array(
                array(
                    'XML_ID' => 'EAC',
                    'VALUE' => 'EAC',
                ),
                array(
                    'XML_ID' => 'Ростест',
                    'VALUE' => 'Ростест',
                ),
            ),
        ),
        array(
            'IBLOCK_ID' => $iblockId,
            'NAME' => 'Особенности2',
            'CODE' => 'FEATURES2',
            'MULTIPLE' => 'Y',
            'SORT' => 100,
        ),
    );
    foreach ($aIblockProperties as $aFields) {
        $aFilter = array('IBLOCK_ID' => $aFields['IBLOCK_ID'], 'CODE' => $aFields['CODE']);
        $oDbRes = CIBlockProperty::GetList(array(), $aFilter);
        if ($aDbRes = $oDbRes->fetch()) {
            echo 'Свойство "'.$aFields['NAME'].'" уже существует<br>';
        } elseif ($iPropertyId = $oCIBlockProperty->Add($aFields)) {
            echo 'Успешно создано свойство "'.$aFields['NAME'].'"<br>';

            if (isset($aFields["ITEMS"]) && !empty($aFields["ITEMS"])) {
                foreach ($aFields["ITEMS"] as $aEnumFields) {
                    $aEnumFields["PROPERTY_ID"] = $iPropertyId;
                    if (CIBlockPropertyEnum::Add($aEnumFields)) {
                        echo 'Успешно создано значение "'.$aEnumFields["VALUE"].'" свойства "'.$aFields["NAME"].'"<br>';
                    } else {
                        echo 'Не удалось создать значение "'.$aEnumFields["VALUE"].'" свойства "'.$aFields["NAME"].'"<br>';
                    }
                }
            }
        } else {
            echo 'Не удалось создать свойство "'.$aFields['NAME'].'"<br>';
            echo 'Error: '.$oCIBlockProperty->LAST_ERROR.'<br>';
        }
    }

    $aIblockProperties = array(
        array(
            "IBLOCK_ID" => $iblockId,
            "CODE" => "TEXT_UNDER_PHOTO",
        ),
    );
    foreach ($aIblockProperties as $aFields) {
        $aFilter = array("IBLOCK_ID" => $aFields["IBLOCK_ID"], "CODE" => $aFields["CODE"]);
        $oDbRes = CIBlockProperty::GetList(array(), $aFilter);
        if ($aDbRes = $oDbRes->fetch()) {
            if (CIBlockProperty::Delete($aDbRes["ID"])) {
                echo 'Успешно удалено свойство "'.$aFields["CODE"].'"<br>';
            } else {
                echo 'Не удалось удалить свойство "'.$aFields["CODE"].'"<br>';
            }
        } else {
            echo 'Свойство "'.$aFields["CODE"].'" не существует<br>';
        }
    }

    $oCIBlockProperty = new CIBlockProperty;
    $aIblockProperties = array(
        array(
            "IBLOCK_ID" => $iblockId,
            "CODE" => "features",
            'USER_TYPE' => 'HTML',
        ),
        array(
            "IBLOCK_ID" => $iblockId,
            "CODE" => "contents_of_delivery",
            'USER_TYPE' => 'HTML',
        ),
    );
    foreach ($aIblockProperties as $aFields) {
        $aFilter = array("IBLOCK_ID" => $aFields["IBLOCK_ID"], "CODE" => $aFields["CODE"]);
        $oDbRes = CIBlockProperty::GetList(array(), $aFilter);
        if ($aDbRes = $oDbRes->fetch()) {
            if ($oCIBlockProperty->Update($aDbRes["ID"], $aFields)) {
                echo 'Успешно обновлено свойство "'.$aFields["CODE"].'"<br>';
            } else {
                echo 'Не удалось обновить свойство "'.$aFields["CODE"].'"<br>';
                echo 'Error: '.$oCIBlockProperty->LAST_ERROR.'"<br>';
            }
        } else {
            echo 'Свойство "'.$aFields["CODE"].'" не существует<br>';
        }
    }
}
