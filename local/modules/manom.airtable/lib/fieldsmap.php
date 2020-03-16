<?php

namespace Manom\Airtable;

/**
 * Class FieldsMap
 * @package Manom\Airtable
 */
class FieldsMap
{
    /**
     * FieldsMap constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getMap(): array
    {
        $fields = array(
            'NAME' => 'Название',
            'PREVIEW_TEXT' => 'Анонс',
            'DETAIL_TEXT' => 'Описание',
            'IBLOCK_SECTION_ID' => 'Подкатегория',
        );

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
        );

        return array('fields' => $fields, 'properties' => $properties);
    }
}
