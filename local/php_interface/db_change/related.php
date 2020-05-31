<?php

use \Manom\Airtable\AirtablePropertiesLinkTable;

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

    $CIBlockProperty = new CIBlockProperty;

    if (!empty($iblockId)) {
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

    $iblockId = 6;
    $aIblockProperties = array(
        array(
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Связанные товары (Цвет)",
            "CODE" => "RELATED_COLOR",
            'PROPERTY_TYPE' => 'E',
            'LINK_IBLOCK_ID' => $iblockId,
            'MULTIPLE' => 'Y',
            'SORT' => 5000,
        ),
        array(
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Связанные товары (Объём памяти)",
            "CODE" => "RELATED_MEMORY",
            'PROPERTY_TYPE' => 'E',
            'LINK_IBLOCK_ID' => $iblockId,
            'MULTIPLE' => 'Y',
            'SORT' => 5000,
        ),
        array(
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Связанные товары (Объём накопителя)",
            "CODE" => "RELATED_MEMORY2",
            'PROPERTY_TYPE' => 'E',
            'LINK_IBLOCK_ID' => $iblockId,
            'MULTIPLE' => 'Y',
            'SORT' => 5000,
        ),
        array(
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Связанные товары (Процессор)",
            "CODE" => "RELATED_CPU",
            'PROPERTY_TYPE' => 'E',
            'LINK_IBLOCK_ID' => $iblockId,
            'MULTIPLE' => 'Y',
            'SORT' => 5000,
        ),
        array(
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Связанные товары (Графический процессор)",
            "CODE" => "RELATED_GPU",
            'PROPERTY_TYPE' => 'E',
            'LINK_IBLOCK_ID' => $iblockId,
            'MULTIPLE' => 'Y',
            'SORT' => 5000,
        ),
        array(
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Связанные товары (Диагональ экрана)",
            "CODE" => "RELATED_SCREEN",
            'PROPERTY_TYPE' => 'E',
            'LINK_IBLOCK_ID' => $iblockId,
            'MULTIPLE' => 'Y',
            'SORT' => 5000,
        ),
        array(
            "IBLOCK_ID" => $iblockId,
            "NAME" => "Связанные товары (Наличие LTE)",
            "CODE" => "RELATED_LTE",
            'PROPERTY_TYPE' => 'E',
            'LINK_IBLOCK_ID' => $iblockId,
            'MULTIPLE' => 'Y',
            'SORT' => 5000,
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

    if (!CModule::IncludeModule("manom.airtable")) {
        echo "Не удалось подключить модуль manom.airtable<br>";
        die;
    }

    $currentProperties = array();
    $result = AirtablePropertiesLinkTable::getList(array(
        'order' => array('id' => 'ASC'),
        'filter' => array(),
        'select' => array('id', 'airtable', 'bitrix'),
    ));
    while ($row = $result->fetch()) {
        $currentProperties[$row['airtable']] = array(
            'id' => (int)$row['id'],
            'airtable' => $row['airtable'],
            'bitrix' => $row['bitrix'],
        );
    }

    $properties = array(
        'MORE_PHOTO' => 'Изображения',
        'CML2_ARTICLE' => 'Артикул',
        'simcard' => 'Тип SIM-карты',
        'operat_sis' => 'Операционная система',
        'tip_korpusa' => 'Тип корпуса',
        'PROCESSOR' => 'Процессор',
        'SSD_VALUE' => 'Объем накопителя',
        'size' => 'Диагональ',
        'SCREEN_RESOLUTION' => 'Разрешение',
        'display_brightness' => 'Яркость',
        'display_contrast' => 'Контрастность',
        'display_density_ppi' => 'Плотность пикселей на дюйм',
        'SCREEN_TYPE' => 'Тип дисплея',
        'display_multitouch_option' => 'Multi-Touch',
        'display_techs' => 'Технологии дисплея',
        'camera_aperture_size' => 'Диафрагма, основная камера',
        'camera_image_stabilization' => 'Стабилизация изображения',
        'vspyshka' => 'Вспышка',
        'camera_lens' => 'Объектив',
        'lens_cover_type' => 'Защита объектива',
        'osobenosti_kamer' => 'Особенности камер',
        'camera_video_resolution' => 'Разрешение видео',
        'video_function' => 'Функции видео',
        'sensors' => 'Датчики',
        'battery_type' => 'Тип аккумулятора',
        'charging_type' => 'Зарядка',
        'audiotime_battery' => 'Воспроизведение аудио',
        'playvideo_time_batttery' => 'Воспроизведение видео',
        'material_type' => 'Материал',
        'length' => 'Длина',
        'width' => 'Ширина',
        'thickness' => 'Толщина',
        'Weight' => 'Вес',
        'id_verification' => 'Аутентификация',
        'water_resistance' => 'Уровень защиты от влаги',
        'GARANTY' => 'Гарантия',
        'PROC_FREQUENCY' => 'Частота процессора',
        'PROC_TURBOBOOST' => 'Ускорение Turbo Boost',
        'PROC_CORE_AMOUNT' => 'Количество ядер процессора',
        'RAM_TYPE' => 'Тип оперативной памяти',
        'RAM_FREQUENCY' => 'Частота оперативной памяти',
        'SSD_TYPE' => 'Тип накопителя',
        'GPU_TYPE' => 'Тип графического процессора',
        'GPU_NAME' => 'Графический процессор',
        'GPU_MEMORY_VALUE' => 'Объем видеопамяти',
        'interface_dvivga' => 'Поддержка интерфейсов',
        'monitors_support' => 'Поддержка доп. мониторов',
        'web_camera' => 'Веб-камера',
        'audio' => 'Аудио',
        'charge_socket' => 'Разъем питания',
        'charge_value' => 'Ёмкость аккумулятора',
        'charge_adapter_power' => 'Мощность адаптера',
        'loop_size' => 'Размер ремешка',
        'wireless_net' => 'Беспроводная сеть',
        'battery_life' => 'Время автономной работы',
        'material_loop' => 'Материал ремешка',
        'proof_level' => 'Уровень защиты',
        'wireless_connection' => 'Беспроводное подключение',
        'source_link' => 'Ссылка на источник',
        'indication' => 'Индикация',
        'number_ear_pads' => 'Количество сменных амбушюр',
        'material_ear_pads' => 'Материал амбушюр',
        'water_protection' => 'Защита от воды',
        'active_noise_reduction' => 'Активное шумоподавление',
        'type_emitter' => 'Тип излучателя',
        'headphone_type' => 'Тип наушников',
        'navigation' => 'Навигация',
        'clock_face' => 'Циферблат',
        'fingerprint_scanner' => 'Сканер отпечатка пальца',
        'keyboard_backlight' => 'Подсветка клавиш клавиатуры',
        'bluetooth_technology' => 'Технология Bluetooth',
        'wi_fi_technology' => 'Технология Wi-Fi',
        'thunderbolt_number' => 'Количество Thunderbolt 3 (USB‑C)',
        'display_backlight' => 'Подсветка дисплея',
        'max_memory_size' => 'Макс. объем памяти',
        'built_in_acoustics' => 'Встроенная акустика',
        'headphone_jack' => 'Разъем для наушников',
        'cpu_features' => 'Особенности процессора',
        'data_transfer' => 'Передача данных',
        'country_manufacture' => 'Страна производитель',
        'add_opportunities' => 'Доп. возможности',
        'shipping_weight' => 'Вес с упаковкой',
        'geo_position' => 'Геопозиция',
        'cellular_and_wireless' => 'Сотовая и беспроводная сеть',
        'zoom_video' => 'Зум (видео)',
        'aperture_front_camera' => 'Диафрагма, фронтальная камера',
        'front_camera' => 'Фронтальная камера',
        'main_camera' => 'Основная камера',
        'sim_card_quantity' => 'Количество SIM карт',
        'release_year' => 'Год релиза',
        'features' => 'Особенности',
        'contents_of_delivery' => 'Комплект поставки',
        'color' => 'Цвет',
        'memory_size' => 'Объём памяти',
        'brand' => 'Бренд',
        'SCREEN_SiZE' => 'Диагональ экрана',
        'CERTIFICATES' => 'Сертификаты',
        'FEATURES2' => 'Особенности 2',
        'RELATED_COLOR' => 'Связанные товары (Цвет)',
        'RELATED_MEMORY' => 'Связанные товары (Объём памяти)',
        'RELATED_MEMORY2' => 'Связанные товары (Объём накопителя)',
        'RELATED_CPU' => 'Связанные товары (Процессор)',
        'RELATED_GPU' => 'Связанные товары (Графический процессор)',
        'RELATED_SCREEN' => 'Связанные товары (Диагональ экрана)',
        'RELATED_LTE' => 'Связанные товары (Наличие LTE)',
    );

    foreach ($properties as $bitrix => $airtable) {
        if (empty($currentProperties[$airtable])) {
            $result = AirtablePropertiesLinkTable::add(array(
                'airtable' => $airtable,
                'bitrix' => $bitrix,
            ));
            if ($result->isSuccess()) {
                echo "Успешно создана привязка для свойства \"".$airtable."\"<br>";
            } else {
                echo "Не удалось создать привязку для свойства \"".$airtable."\"<br>";
            }
        } else {
            echo "Привязка для свойства \"".$airtable."\" уже существует<br>";
        }
    }
}
