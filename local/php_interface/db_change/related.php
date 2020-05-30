<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

global $USER;
if ($USER->IsAdmin()) {
    if (!CModule::IncludeModule("iblock")) {
        echo "Не удалось подключить модуль iblock<br>";
        die;
    }

    $CIBlockType = new CIBlockType;
    $aIblockTypes = array(
        array(
            "ID" => "references",
            "SORT" => 500,
            "SECTIONS" => "Y",
            "LANG" => array(
                "ru" => array(
                    "NAME" => "Справочники",
                ),
                "en" => array(
                    "NAME" => "Справочники",
                ),
            ),
        ),
    );
    foreach ($aIblockTypes as $aFields) {
        $aFilter = array("ID" => $aFields["ID"]);
        $oDbRes = CIBlockType::GetList(array(), $aFilter);
        if ($aDbRes = $oDbRes->fetch()) {
            echo "Тип инфоблоков \"".$aDbRes["ID"]."\" уже существует<br>";
        } elseif ($CIBlockType->Add($aFields)) {
            echo "Успешно создан тип инфоблоков \"".$aFields["ID"]."\"<br>";
        } else {
            echo "Не удалось создать тип инфоблоков \"".$aFields["ID"]."\"<br>";
            echo "Error: ".$CIBlockType->LAST_ERROR."<br>";
        }
    }

    $CIBlock = new CIBlock;
    $aIblocks = array(
        array(
            "IBLOCK_TYPE_ID" => "references",
            "LID" => "s1",
            "CODE" => "colors",
            "NAME" => "Цвет",
            "LIST_PAGE_URL" => "#SITE_DIR#/",
            "SECTION_PAGE_URL" => "#SITE_DIR#/",
            "DETAIL_PAGE_URL" => "#SITE_DIR#/",
            "INDEX_ELEMENT" => "N",
            "INDEX_SECTION" => "N",
            "GROUP_ID" => array(2 => "R"),
        ),
    );
    foreach ($aIblocks as $aFields) {
        $result = CIBlock::GetList(array(), array('CODE' => $aFields['CODE']));
        if ($row = $result->Fetch()) {
            $iblockId = $row['ID'];

            echo "Инфоблок \"".$aFields["NAME"]."\" уже существует<br>";
        } elseif ($iblockId = $CIBlock->Add($aFields)) {
            echo "Успешно создан инфоблок \"".$aFields["NAME"]."\"<br>";
        } else {
            echo "Не удалось создать инфоблок \"".$aFields["NAME"]."\"<br>";
            echo "Error: ".$CIBlock->LAST_ERROR."<br>";
        }
    }

    if (!empty($iblockId)) {
        $CIBlockProperty = new CIBlockProperty;
        $aIblockProperties = array(
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Код цвета",
                "CODE" => "COLOR_CODE",
            ),
        );
        foreach ($aIblockProperties as $aFields) {
            $aFilter = array("IBLOCK_ID" => $aFields["IBLOCK_ID"], "CODE" => $aFields["CODE"]);
            $oDbRes = CIBlockProperty::GetList(array(), $aFilter);
            if ($aDbRes = $oDbRes->fetch()) {
                echo "Свойство \"".$aFields["NAME"]."\" уже существует<br>";
            } elseif ($CIBlockProperty->Add($aFields)) {
                echo "Успешно создано свойство \"".$aFields["NAME"]."\"<br>";
            } else {
                echo "Не удалось создать свойство \"".$aFields["NAME"]."\"<br>";
                echo "Error: ".$CIBlockProperty->LAST_ERROR."<br>";
            }
        }

        $CIBlockElement = new CIBlockElement;
        $aIblockElements = array(
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Серебряный",
                'CODE' => 'silver',
                'SORT' => 0,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#c0c0c0',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Черный",
                'CODE' => 'black',
                'SORT' => 1,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#000000',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Серый",
                'CODE' => 'gray',
                'SORT' => 2,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#808080',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Белый",
                'CODE' => 'white',
                'SORT' => 3,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#ffffff',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Красный",
                'CODE' => 'red',
                'SORT' => 4,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#E82435',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Оранжевый",
                'CODE' => 'orange',
                'SORT' => 5,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#ffa500',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Желтый",
                'CODE' => 'yellow',
                'SORT' => 6,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#ffff00',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Зеленый",
                'CODE' => 'green',
                'SORT' => 7,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#76B821',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Голубой",
                'CODE' => 'blue',
                'SORT' => 8,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#0000ff',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Синий",
                'CODE' => 'dark-blue',
                'SORT' => 9,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#000080',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Фиолетовый",
                'CODE' => 'purple',
                'SORT' => 10,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#800080',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Коричневый",
                'CODE' => 'brown',
                'SORT' => 11,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#a52a2a',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Разноцветный",
                'CODE' => 'multicolor',
                'SORT' => 12,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Розовый",
                'CODE' => 'pink',
                'SORT' => 13,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#ffc0cb',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Бежевый",
                'CODE' => 'beige',
                'SORT' => 14,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#f5f5dc',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Золотой",
                'CODE' => 'gold',
                'SORT' => 15,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#ffd700',
                ),
            ),
            array(
                "IBLOCK_ID" => $iblockId,
                "NAME" => "Бордовый",
                'CODE' => 'burgundy',
                'SORT' => 16,
                'PROPERTY_VALUES' => array(
                    'COLOR_CODE' => '#800020',
                ),
            ),
        );
        foreach ($aIblockElements as $aFields) {
            $aFilter = array("IBLOCK_ID" => $aFields["IBLOCK_ID"], "CODE" => $aFields["CODE"]);
            $aSelect = array("IBLOCK_ID", "ID");
            $oDbRes = CIBlockElement::GetList(array(), $aFilter, false, false, $aSelect);
            if ($aDbRes = $oDbRes->fetch()) {
                echo "Элемент \"".$aFields["NAME"]."\" уже существует<br>";
            } elseif ($CIBlockElement->Add($aFields)) {
                echo "Успешно создан элемент \"".$aFields["NAME"]."\"<br>";
            } else {
                echo "Не удалось создать элемент \"".$aFields["NAME"]."\"<br>";
                echo "Error: ".$CIBlockElement->LAST_ERROR."<br>";
            }
        }
    }
}
