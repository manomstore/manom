<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

global $USER;
if ($USER->IsAdmin()) {
    $iblockId = 6;
    $oCIBlockProperty = new CIBlockProperty;
    $aIblockProperties = array(
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Ссылка на источник', 'CODE' => 'source_link'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Индикация', 'CODE' => 'indication'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Количество сменных амбушюр', 'CODE' => 'number_ear_pads'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Материал амбушюр', 'CODE' => 'material_ear_pads'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Защита от воды', 'CODE' => 'water_protection'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Активное шумоподавление', 'CODE' => 'active_noise_reduction'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Тип излучателя', 'CODE' => 'type_emitter'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Тип наушников', 'CODE' => 'headphone_type'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Навигация', 'CODE' => 'navigation'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Циферблат', 'CODE' => 'clock_face'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Сканер отпечатка пальца', 'CODE' => 'fingerprint_scanner'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Подсветка клавиш клавиатуры', 'CODE' => 'keyboard_backlight'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Технология Bluetooth', 'CODE' => 'bluetooth_technology'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Технология Wi-Fi', 'CODE' => 'wi_fi_technology'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Количество Thunderbolt 3 (USB‑C)', 'CODE' => 'thunderbolt_number'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Подсветка дисплея', 'CODE' => 'display_backlight'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Макс. объем памяти', 'CODE' => 'max_memory_size'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Встроенная акустика', 'CODE' => 'built_in_acoustics'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Разъем для наушников', 'CODE' => 'headphone_jack'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Особенности процессора', 'CODE' => 'cpu_features'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Передача данных', 'CODE' => 'data_transfer'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Страна производитель', 'CODE' => 'country_manufacture'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Доп. возможности', 'CODE' => 'add_opportunities'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Вес с упаковкой', 'CODE' => 'shipping_weight'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Геопозиция', 'CODE' => 'geo_position'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Сотовая и беспроводная сеть', 'CODE' => 'cellular_and_wireless'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Зум (видео)', 'CODE' => 'zoom_video'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Диафрагма, фронтальная камера', 'CODE' => 'aperture_front_camera'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Фронтальная камера', 'CODE' => 'front_camera'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Основная камера', 'CODE' => 'main_camera'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Количество SIM карт', 'CODE' => 'sim_card_quantity'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Год релиза', 'CODE' => 'release_year'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Особенности', 'CODE' => 'features'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Комплект поставки', 'CODE' => 'contents_of_delivery'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Цвет', 'CODE' => 'color'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Объём памяти', 'CODE' => 'memory_size'),
        array('IBLOCK_ID' => $iblockId, 'NAME' => 'Бренд', 'CODE' => 'brand'),
    );
    foreach ($aIblockProperties as $aFields) {
        $aFilter = array('IBLOCK_ID' => $aFields['IBLOCK_ID'], 'CODE' => $aFields['CODE']);
        $oDbRes = CIBlockProperty::GetList(array(), $aFilter);
        if ($aDbRes = $oDbRes->fetch()) {
            echo 'Свойство "'.$aFields['NAME'].'" уже существует<br>';
        } elseif ($iPropertyId = $oCIBlockProperty->Add($aFields)) {
            echo 'Успешно создано свойство "'.$aFields['NAME'].'"<br>';
        } else {
            echo 'Не удалось создать свойство "'.$aFields['NAME'].'"<br>';
            echo 'Error: '.$oCIBlockProperty->LAST_ERROR.'<br>';
        }
    }

    $properties = array(
        'MORE_PHOTO',
        'CML2_ARTICLE',
        'simcard Тип',
        'operat_sis',
        'tip_korpusa',
        'PROCESSOR',
        'SSD_VALUE',
        'size',
        'SCREEN_RESOLUTION',
        'display_brightness',
        'display_contrast',
        'display_density_ppi',
        'SCREEN_TYPE',
        'display_multitouch_option',
        'display_techs',
        'camera_aperture_size',
        'camera_image_stabilization',
        'vspyshka',
        'camera_lens',
        'lens_cover_type',
        'osobenosti_kamer',
        'camera_video_resolution',
        'video_function',
        'sensors',
        'battery_type',
        'charging_type',
        'audiotime_battery',
        'playvideo_time_batttery',
        'material_type',
        'length',
        'width',
        'thickness',
        'Weight',
        'id_verification',
        'water_resistance',
        'GARANTY',
        'PROC_FREQUENCY',
        'PROC_TURBOBOOST',
        'PROC_CORE_AMOUNT',
        'RAM_TYPE',
        'RAM_FREQUENCY',
        'SSD_TYPE',
        'GPU_TYPE',
        'GPU_NAME',
        'GPU_MEMORY_VALUE',
        'interface_dvivga',
        'monitors_support',
        'web_camera',
        'audio',
        'charge_socket',
        'charge_value',
        'charge_adapter_power',
        'loop_size',
        'wireless_net',
        'battery_life',
        'material_loop',
        'proof_level',
        'wireless_connection',
        'source_link',
        'indication',
        'number_ear_pads',
        'material_ear_pads',
        'water_protection',
        'active_noise_reduction',
        'type_emitter',
        'headphone_type',
        'navigation',
        'clock_face',
        'fingerprint_scanner',
        'keyboard_backlight',
        'bluetooth_technology',
        'wi_fi_technology',
        'thunderbolt_number',
        'display_backlight',
        'max_memory-size',
        'built_in_acoustics',
        'headphone_jack',
        'cpu_features',
        'data_transfer',
        'country_manufacture',
        'add_opportunities',
        'shipping_weight',
        'geo_position',
        'cellular_and_wireless',
        'zoom_video',
        'aperture_front_camera',
        'front_camera',
        'main_camera',
        'sim_card_quantity',
        'release_year',
        'features',
        'contents_of_delivery',
        'color',
        'memory_size',
        'brand',
    );
    $filter = array('IBLOCK_ID' => 6);
    $result = CIBlockProperty::GetList(array(), $filter);
    while ($row = $result->Fetch()) {
        if (empty($row['CODE'])) {
            CIBlockProperty::Delete($row['ID']);
            continue;
        }

        if (in_array($row['CODE'], $properties, true)) {
            $sort = 100;
        } elseif ($row['PROPERTY_TYPE'] === 'N') {
            $sort = 600;
        } elseif ($row['USER_TYPE'] === 'HTML') {
            $sort = 700;
        } elseif ($row['PROPERTY_TYPE'] === 'F') {
            $sort = 800;
        } elseif ($row['PROPERTY_TYPE'] === 'E') {
            $sort = 900;
        } elseif ($row['PROPERTY_TYPE'] === 'L') {
            $sort = 1000;
        } else {
            $sort = 1100;
        }

        $oCIBlockProperty->Update($row['ID'], array('SORT' => $sort));
    }
}
