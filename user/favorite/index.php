<?php

use Manom\Content;

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

$APPLICATION->SetTitle('Избранные товары');

$content = new Content();

$favoritesId = getProdListFavoritAndCompare('UF_FAVORITE_ID');
global $favoriteFilter;
$favoriteFilter = array('ID' => $favoritesId, '>CATALOG_PRICE_1' => 0);
?>
<div class="content">
    <div class="personal container" style="position: relative;">
        <div class="preloaderCatalog">
            <div class="windows8">
                <div class="wBall" id="wBall_1">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_2">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_3">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_4">
                    <div class="wInnerBall"></div>
                </div>
                <div class="wBall" id="wBall_5">
                    <div class="wInnerBall"></div>
                </div>
            </div>
        </div>
        <div class="personal-main">
            <aside class="personal__aside">
                <h1 class="personal__title">Личный кабинет</h1>
                <?php if ($USER->IsAuthorized()): ?>
                    <a
                            href="/user/profile.php"
                            id="personal-nav__item1"
                            class="personal-nav__item personal-nav__name"
                            data-id="pb-info"
                    >
                        Мои настройки
                    </a>
                    <p class="personal-nav__name">Покупки:</p>
                    <a href="/user/history.php" id="personal-nav__item2" class="personal-nav__item">История покупок
                    </a>
                    <a href="/user/favorite/" id="personal-nav__item4" class="personal-nav__item">Товары в избранном
                    </a>
                    <a href="/catalog/compare/" id="personal-nav__item4" class="personal-nav__item">Сравнение
                        товаров
                    </a>

                    <?php /*
                    <p class="personal-nav__name">Моя активность:</p>
                    <p id="personal-nav__item4" class="personal-nav__item" data-id="pb-comments">Мои отзывы</p>
                    */ ?>
                <?php else: ?>
                    <a href="/auth/" class="personal-nav__item">Авторизация</a>
                <?php endif; ?>
            </aside>

            <?php if (empty($favoritesId)): ?>
                <?php if ($_REQUEST['ajaxCal'] === 'Y') {
                    $GLOBALS['APPLICATION']->RestartBuffer();
                } ?>
                <section class="catalog-block">
                    <?php if ($_REQUEST['ajaxCal'] !== 'Y') { ?>
                        <h2 class="pb-info__title">Избранные товары:</h2>
                    <?php } ?>
                    <div class="addToFavoriteListOnFP_NOT_ITEM"></div>
                    <p class="notetext" style="padding: 20px 0;">В избранном отсутствуют товары</p>
                </section>
                <?php if ($_REQUEST['ajaxCal'] === 'Y') {
                    die();
                } ?>
            <?php else: ?>
                <?php if ($_REQUEST['ajaxCal'] === 'Y') {
                    $GLOBALS['APPLICATION']->RestartBuffer();
                } ?>
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:catalog.section',
                    'favorite',
                    array(
                        'ACTION_VARIABLE' => '',
                        'ADD_PICT_PROP' => '',
                        'ADD_PROPERTIES_TO_BASKET' => 'N',
                        'ADD_SECTIONS_CHAIN' => 'N',
                        'ADD_TO_BASKET_ACTION' => '',
                        'AJAX_MODE' => 'N',
                        'AJAX_OPTION_ADDITIONAL' => '',
                        'AJAX_OPTION_HISTORY' => 'N',
                        'AJAX_OPTION_JUMP' => 'N',
                        'AJAX_OPTION_STYLE' => 'N',
                        'BACKGROUND_IMAGE' => '',
                        'BASKET_URL' => '',
                        'BROWSER_TITLE' => '',
                        'CACHE_FILTER' => 'N',
                        'CACHE_GROUPS' => 'Y',
                        'CACHE_TIME' => 36000000,
                        'CACHE_TYPE' => 'A',
                        'COMPATIBLE_MODE' => 'Y',
                        'CONVERT_CURRENCY' => 'N',
                        'CUSTOM_FILTER' => '',
                        'DETAIL_URL' => '',
                        'DISABLE_INIT_JS_IN_COMPONENT' => 'Y',
                        'DISPLAY_BOTTOM_PAGER' => 'Y',
                        'DISPLAY_COMPARE' => 'N',
                        'DISPLAY_TOP_PAGER' => 'N',
                        'ELEMENT_SORT_FIELD' => $content->getSortValue("field"),
                        'ELEMENT_SORT_FIELD2' => $content->getSortValue("field2"),
                        'ELEMENT_SORT_ORDER' => $content->getSortValue("order"),
                        'ELEMENT_SORT_ORDER2' => $content->getSortValue("order2"),
                        'SORT_LIST' => $content->getSortList(),
                        'ENLARGE_PRODUCT' => '',
                        'FILE_404' => '',
                        'FILTER_NAME' => 'favoriteFilter',
                        'HIDE_NOT_AVAILABLE' => 'Y',
                        'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
                        'IBLOCK_ID' => 6,
                        'IBLOCK_TYPE' => 'catalog',
                        'INCLUDE_SUBSECTIONS' => 'Y',
                        'LABEL_PROP' => array(),
                        'LAZY_LOAD' => 'N',
                        'LINE_ELEMENT_COUNT' => 0,
                        'LOAD_ON_SCROLL' => 'N',
                        'MESSAGE_404' => '',
                        'MESS_BTN_ADD_TO_BASKET' => '',
                        'MESS_BTN_BUY' => '',
                        'MESS_BTN_DETAIL' => '',
                        'MESS_BTN_SUBSCRIBE' => '',
                        'MESS_NOT_AVAILABLE' => '',
                        'META_DESCRIPTION' => '',
                        'META_KEYWORDS' => '',
                        'OFFERS_CART_PROPERTIES' => array(
                            'this_prod_model',
                            'watch_display_size',
                            'HDD',
                            'BLOG_POST_ID',
                            'SALES_ITEM',
                            'CML2_ARTICLE',
                            'CML2_BASE_UNIT',
                            'size_list',
                            'item_code',
                            'BLOG_COMMENTS_CNT',
                            'material_list',
                            'NOT_EXPORT_YM',
                            'NEW_ITEM',
                            'OS',
                            'CML2_MANUFACTURER',
                            'PROC',
                            'SCREEN_RES',
                            'CML2_TRAITS',
                            'laptop_series',
                            'CML2_TAXES',
                            'PRODUCT_DAY',
                            'CML2_ATTRIBUTES',
                            'COLOR_REF',
                            'ciferblat',
                            'BUNDLE_BOX',
                            'CML2_BAR_CODE',
                        ),
                        'OFFERS_FIELD_CODE' => array(
                            'ID',
                            'CODE',
                            'XML_ID',
                            'NAME',
                            'TAGS',
                            'SORT',
                            'PREVIEW_TEXT',
                            'PREVIEW_PICTURE',
                            'DETAIL_TEXT',
                            'DETAIL_PICTURE',
                        ),
                        'OFFERS_LIMIT' => 0,
                        'OFFERS_PROPERTY_CODE' => array(
                            'this_prod_model',
                            'watch_display_size',
                            'HDD',
                            'BLOG_POST_ID',
                            'CML2_ARTICLE',
                            'CML2_BASE_UNIT',
                            'size_list',
                            'MORE_PHOTO',
                            'item_code',
                            'BLOG_COMMENTS_CNT',
                            'material_list',
                            'NOT_EXPORT_YM',
                            'OS',
                            'CML2_MANUFACTURER',
                            'PROC',
                            'SCREEN_RES',
                            'CML2_TRAITS',
                            'laptop_series',
                            'CML2_TAXES',
                            'FILES',
                            'CML2_ATTRIBUTES',
                            'COLOR_REF',
                            'ciferblat',
                            'BUNDLE_BOX',
                            'CML2_BAR_CODE',
                            'ipad_model',
                            'case_size_filter',
                        ),
                        'OFFERS_SORT_FIELD' => 'sort',
                        'OFFERS_SORT_FIELD2' => 'id',
                        'OFFERS_SORT_ORDER' => 'asc',
                        'OFFERS_SORT_ORDER2' => 'desc',
                        'PAGER_BASE_LINK_ENABLE' => 'Y',
                        'PAGER_DESC_NUMBERING' => 'N',
                        'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
                        'PAGER_SHOW_ALL' => 'N',
                        'PAGER_SHOW_ALWAYS' => 'N',
                        'PAGER_TEMPLATE' => 'catalog_section',
                        'PAGER_TITLE' => 'Товары',
                        'PAGE_ELEMENT_COUNT' => $content->getPageCount(),
                        'PAGE_COUNT_LIST' => $content->getPageCountList(),
                        'PARTIAL_PRODUCT_PROPERTIES' => 'N',
                        'PRICE_CODE' => array('Цена продажи', 'РРЦ'),
                        'PRICE_VAT_INCLUDE' => 'Y',
                        'PRODUCT_BLOCKS_ORDER' => '',
                        'PRODUCT_DISPLAY_MODE' => 'N',
                        'PRODUCT_ID_VARIABLE' => '',
                        'PRODUCT_PROPERTIES' => array(
                            'NEW_PRODUCT',
                            'STOCK_PRODUCT',
                            'ACESS',
                            'RECOM',
                            'A_N_Q',
                            'DOP_SERV',
                            'ACESS_STR',
                            'brand_rs',
                            'CML2_MANUFACTURER',
                            'watch_display_size',
                            'loop_size',
                            'SELL_PROD',
                            'YOUTUBE',
                            'camera_image_stabilization',
                            'CML2_TAXES',
                            'PRODUCT_OF_THE_DAY',
                            'CML2_ATTRIBUTES',
                            'SELLOUT',
                        ),
                        'PRODUCT_PROPS_VARIABLE' => '',
                        'PRODUCT_QUANTITY_VARIABLE' => '',
                        'PRODUCT_ROW_VARIANTS' => '',
                        'PRODUCT_SUBSCRIPTION' => 'N',
                        'PROPERTY_CODE' => array(
                            'model',
                            'CML2_ARTICLE',
                            'TOP_FIELD_2',
                            'SCREEN_SiZE',
                            'SCREEN_RESOLUTION',
                            'SCREEN_TYPE',
                            'display_density_ppi',
                            'DELIVERY_DETAIL_CART',
                            'BLOG_POST_ID',
                            'lan_socketrj45',
                            'microsoft',
                            'display_multitouch_option',
                            'touchbar_apple',
                            'automatic_on',
                            'movements_accelerometer',
                            'ACESS',
                            'acoustic_type',
                            'audio',
                            'id_verification',
                            'batteries_inbox',
                            'BATTARY',
                            'wireless_mouse',
                            'wireless_net',
                            'wireless_connection',
                            'wireless_gamepad',
                            'RECOM',
                            'buka_support',
                            'accessoires_kit',
                            'charge_waiting_time',
                            'talk_time_battery',
                            'web_camera',
                            'Weight',
                            'A_N_Q',
                            'audiotime_battery',
                            'playvideo_time_batttery',
                            'battery_life',
                            'built_in_processor',
                            'hdmi_input',
                            'stylus_height',
                            'hdmi_output',
                            'toslink_optical_output',
                            'GARANTY',
                            'voice_control',
                            'voice_accelerometer',
                            'voice_dial',
                            'voice_notifications',
                            'GPU_NAME',
                            'gsensor',
                            'sensors',
                            'double_optical_sensors',
                            'size',
                            'diapozon_chastot',
                            'selfie_camera_aperture_size',
                            'camera_aperture_size',
                            'length',
                            'cable_length',
                            'essentials',
                            'DOP_SERV',
                            'charge_value',
                            'hdd_value',
                            'camera_video_recording',
                            'charging_type',
                            'charge_usb',
                            'charge_220v',
                            'lens_cover_type',
                            'video_zoom',
                            'camera_zoom_photo',
                            'impedans',
                            'interface_withpc',
                            'handsfree_on',
                            'usbcable_charge',
                            'hdmidigital_cableconnection',
                            'memorycard_type',
                            'hdmi_amount',
                            'thunderbolt_amount',
                            'usb3_amount',
                            'amount_inbox',
                            'speakers_amount',
                            'BLOG_COMMENTS_CNT',
                            'microphone_amount',
                            'PROC_CORE_AMOUNT',
                            'LAPTOP_COMUNICATION',
                            'EQUIPMENT',
                            'display_contrast',
                            'LAPTOP_CONFIGURATIONS',
                            'LAPTOP_CASE',
                            'material_type',
                            'dielekrik_material',
                            'case_material',
                            'provodnik_material',
                            'material_loop',
                            'power_vt',
                            'charge_adapter_power',
                            'MULTIMEDIA',
                            'TEXT_UNDER_PHOTO',
                            'camera_lens',
                            'GPU_MEMORY_VALUE',
                            'SSD_VALUE',
                            'RAM_VALUE',
                            'OS',
                            'ACESS_STR',
                            'PAYMENT_DETAIL_CART',
                            'SUREFACE',
                            'support_devices',
                            'navigation_type',
                            'wifi_support',
                            'monitors_support',
                            'interface_dvivga',
                            'internet_connection',
                            'led_display_type',
                            'display_cover_techs',
                            'socket_cover',
                            'hdmi_port',
                            'usb30typeA_port',
                            'usb31_port',
                            'usb_typec_port',
                            'usbc_port',
                            'browsing_battery_time',
                            'brand_rs',
                            'usefull_for',
                            'CML2_MANUFACTURER',
                            'PROCESSOR',
                            'battery_work',
                            'workable_distance',
                            'watch_display_size',
                            'loop_size',
                            'camera_video_resolution',
                            'selfie_camera_videoresolution_size',
                            'video_slowmo_resolution',
                            'camera_resolution',
                            'camera_selfie_resolution',
                            'connections_lightening_usb',
                            'lightning_socket',
                            'charge_socket',
                            'input_usbc',
                            'Output_usbc',
                            'interface_connector',
                            'SELL_PROD',
                            'double_mic',
                            'sense_controlpanel',
                            'seriel',
                            'noise_reduction_system',
                            'gsm_wifi_tech',
                            'YOUTUBE',
                            'camera_image_stabilization',
                            'CML2_TAXES',
                            'manufactur_country',
                            'display_techs',
                            'camera_tech_essentials',
                            'selfie_camera_techtype',
                            'res4k_support',
                            'hdr_technology',
                            'simcard',
                            'battery_type',
                            'wirelesmouse_type',
                            'TOP_FIELD_3',
                            'GPU_TYPE',
                            'battery_aa_aaa',
                            'cable_type',
                            'SSD_TYPE',
                            'data_storage_type',
                            'lens_type',
                            'RAM_TYPE',
                            'connection_type_bluetooth_usb',
                            'socket_type',
                            'control_type',
                            'PRODUCT_OF_THE_DAY',
                            'thickness',
                            'proof_level',
                            'water_resistance',
                            'PROC_TURBOBOOST',
                            'HHD',
                            'video_format',
                            'video_function',
                            'camera_functions',
                            'selfie_camera_functions',
                            'wirelesscharge_support',
                            'quick_charge',
                            'feedback',
                            'charge_case_mic',
                            'CML2_ATTRIBUTES',
                            'colour',
                            'RAM_FREQUENCY',
                            'PROC_FREQUENCY',
                            'BUNDLE_BOX',
                            'audiosens',
                            'width',
                            'CML2_BAR_CODE',
                            'SCREEN_OF_LAPTOP',
                            'display_brightness',
                            'BS_STR',
                            'SELLOUT',
                            'comp_type_chargebattery',
                            'CML2_BASE_UNIT',
                            'CML2_TRAITS',
                        ),
                        'PROPERTY_CODE_MOBILE' => array(
                            'model',
                            'CML2_ARTICLE',
                            'TOP_FIELD_2',
                            'SCREEN_SiZE',
                            'SCREEN_RESOLUTION',
                            'SCREEN_TYPE',
                            'display_density_ppi',
                            'DELIVERY_DETAIL_CART',
                            'BLOG_POST_ID',
                            'lan_socketrj45',
                            'microsoft',
                            'display_multitouch_option',
                            'touchbar_apple',
                            'automatic_on',
                            'movements_accelerometer',
                            'ACESS',
                            'acoustic_type',
                            'audio',
                            'id_verification',
                            'batteries_inbox',
                            'BATTARY',
                            'wireless_mouse',
                            'wireless_net',
                            'wireless_connection',
                            'wireless_gamepad',
                            'RECOM',
                            'buka_support',
                            'accessoires_kit',
                            'charge_waiting_time',
                            'talk_time_battery',
                            'web_camera',
                            'Weight',
                            'A_N_Q',
                            'audiotime_battery',
                            'playvideo_time_batttery',
                            'battery_life',
                            'built_in_processor',
                            'hdmi_input',
                            'stylus_height',
                            'hdmi_output',
                            'toslink_optical_output',
                            'GARANTY',
                            'voice_control',
                            'voice_accelerometer',
                            'voice_dial',
                            'voice_notifications',
                            'GPU_NAME',
                            'gsensor',
                            'sensors',
                            'double_optical_sensors',
                            'size',
                            'diapozon_chastot',
                            'selfie_camera_aperture_size',
                            'camera_aperture_size',
                            'length',
                            'cable_length',
                            'essentials',
                            'DOP_SERV',
                            'charge_value',
                            'hdd_value',
                            'camera_video_recording',
                            'charging_type',
                            'charge_usb',
                            'charge_220v',
                            'lens_cover_type',
                            'video_zoom',
                            'camera_zoom_photo',
                            'impedans',
                            'interface_withpc',
                            'handsfree_on',
                            'usbcable_charge',
                            'hdmidigital_cableconnection',
                            'memorycard_type',
                            'hdmi_amount',
                            'thunderbolt_amount',
                            'usb3_amount',
                            'amount_inbox',
                            'speakers_amount',
                            'BLOG_COMMENTS_CNT',
                            'microphone_amount',
                            'PROC_CORE_AMOUNT',
                            'LAPTOP_COMUNICATION',
                            'EQUIPMENT',
                            'display_contrast',
                            'LAPTOP_CONFIGURATIONS',
                            'LAPTOP_CASE',
                            'material_type',
                            'dielekrik_material',
                            'case_material',
                            'provodnik_material',
                            'material_loop',
                            'power_vt',
                            'charge_adapter_power',
                            'MULTIMEDIA',
                            'TEXT_UNDER_PHOTO',
                            'camera_lens',
                            'GPU_MEMORY_VALUE',
                            'SSD_VALUE',
                            'RAM_VALUE',
                            'OS',
                            'ACESS_STR',
                            'PAYMENT_DETAIL_CART',
                            'SUREFACE',
                            'support_devices',
                            'navigation_type',
                            'wifi_support',
                            'monitors_support',
                            'interface_dvivga',
                            'internet_connection',
                            'led_display_type',
                            'display_cover_techs',
                            'socket_cover',
                            'hdmi_port',
                            'usb30typeA_port',
                            'usb31_port',
                            'usb_typec_port',
                            'usbc_port',
                            'browsing_battery_time',
                            'brand_rs',
                            'usefull_for',
                            'CML2_MANUFACTURER',
                            'PROCESSOR',
                            'battery_work',
                            'workable_distance',
                            'watch_display_size',
                            'loop_size',
                            'camera_video_resolution',
                            'selfie_camera_videoresolution_size',
                            'video_slowmo_resolution',
                            'camera_resolution',
                            'camera_selfie_resolution',
                            'connections_lightening_usb',
                            'lightning_socket',
                            'charge_socket',
                            'input_usbc',
                            'Output_usbc',
                            'interface_connector',
                            'SELL_PROD',
                            'double_mic',
                            'sense_controlpanel',
                            'seriel',
                            'noise_reduction_system',
                            'gsm_wifi_tech',
                            'YOUTUBE',
                            'camera_image_stabilization',
                            'CML2_TAXES',
                            'manufactur_country',
                            'display_techs',
                            'camera_tech_essentials',
                            'selfie_camera_techtype',
                            'res4k_support',
                            'hdr_technology',
                            'simcard',
                            'battery_type',
                            'wirelesmouse_type',
                            'TOP_FIELD_3',
                            'GPU_TYPE',
                            'battery_aa_aaa',
                            'cable_type',
                            'SSD_TYPE',
                            'data_storage_type',
                            'lens_type',
                            'RAM_TYPE',
                            'connection_type_bluetooth_usb',
                            'socket_type',
                            'control_type',
                            'PRODUCT_OF_THE_DAY',
                            'thickness',
                            'proof_level',
                            'water_resistance',
                            'PROC_TURBOBOOST',
                            'HHD',
                            'video_format',
                            'video_function',
                            'camera_functions',
                            'selfie_camera_functions',
                            'wirelesscharge_support',
                            'quick_charge',
                            'feedback',
                            'charge_case_mic',
                            'CML2_ATTRIBUTES',
                            'colour',
                            'RAM_FREQUENCY',
                            'PROC_FREQUENCY',
                            'BUNDLE_BOX',
                            'audiosens',
                            'width',
                            'CML2_BAR_CODE',
                            'SCREEN_OF_LAPTOP',
                            'display_brightness',
                            'BS_STR',
                            'SELLOUT',
                            'comp_type_chargebattery',
                            'CML2_BASE_UNIT',
                            'CML2_TRAITS',
                        ),
                        'RCM_PROD_ID' => '',
                        'RCM_TYPE' => '',
                        'SECTION_CODE' => '',
                        'SECTION_CODE_PATH' => '',
                        'SECTION_ID' => '',
                        'SECTION_ID_VARIABLE' => '',
                        'SECTION_URL' => '',
                        'SECTION_USER_FIELDS' => array(),
                        'SEF_MODE' => 'Y',
                        'SEF_RULE' => '',
                        'SET_BROWSER_TITLE' => 'N',
                        'SET_LAST_MODIFIED' => 'N',
                        'SET_META_DESCRIPTION' => 'N',
                        'SET_META_KEYWORDS' => 'N',
                        'SET_STATUS_404' => 'N',
                        'SET_TITLE' => 'N',
                        'SHOW_404' => 'N',
                        'SHOW_ALL_WO_SECTION' => 'Y',
                        'SHOW_CLOSE_POPUP' => 'N',
                        'SHOW_DISCOUNT_PERCENT' => 'N',
                        'SHOW_FROM_SECTION' => 'N',
                        'SHOW_MAX_QUANTITY' => 'N',
                        'SHOW_OLD_PRICE' => 'N',
                        'SHOW_PRICE_COUNT' => '1',
                        'SHOW_SLIDER' => 'N',
                        'SLIDER_INTERVAL' => '',
                        'SLIDER_PROGRESS' => 'N',
                        'TEMPLATE_THEME' => '',
                        'USE_ENHANCED_ECOMMERCE' => 'N',
                        'USE_MAIN_ELEMENT_SECTION' => 'N',
                        'USE_PRICE_COUNT' => 'N',
                        'USE_PRODUCT_QUANTITY' => 'N',
                        'AJAX' => $_REQUEST['ajaxCal'] === 'Y',
                        'BLOCK_STYLE' => $_REQUEST['styleBlock'],
                    ),
                    false
                ); ?>
                <?php if ($_REQUEST['ajaxCal'] === 'Y') {
                    die();
                } ?>
            <?php endif; ?>

            <!-- Последние просмотренные товары -->
            <?php /*$APPLICATION->IncludeComponent(
                'bitrix:catalog.products.viewed',
                'cart-product',
                array(
                    'ACTION_VARIABLE' => 'action_cpv',
                    'ADDITIONAL_PICT_PROP_2' => 'MORE_PHOTO',
                    'ADDITIONAL_PICT_PROP_3' => '-',
                    'ADD_PROPERTIES_TO_BASKET' => 'Y',
                    'ADD_TO_BASKET_ACTION' => 'BUY',
                    'BASKET_URL' => '/personal/basket.php',
                    'CACHE_GROUPS' => 'Y',
                    'CACHE_TIME' => '3600',
                    'CACHE_TYPE' => 'A',
                    'CART_PROPERTIES_2' => array(
                        0 => 'NEWPRODUCT',
                        1 => 'NEWPRODUCT,SALELEADER',
                    ),
                    'CART_PROPERTIES_3' => array(
                        0 => 'COLOR_REF',
                        1 => 'SIZES_SHOES',
                    ),
                    'CONVERT_CURRENCY' => 'Y',
                    'CURRENCY_ID' => 'RUB',
                    'DATA_LAYER_NAME' => 'dataLayer',
                    'DEPTH' => '',
                    'DISCOUNT_PERCENT_POSITION' => 'top-right',
                    'ENLARGE_PRODUCT' => 'STRICT',
                    'ENLARGE_PROP_2' => 'NEWPRODUCT',
                    'HIDE_NOT_AVAILABLE' => 'N',
                    'HIDE_NOT_AVAILABLE_OFFERS' => 'N',
                    'IBLOCK_ID' => 6,
                    'IBLOCK_MODE' => 'single',
                    'IBLOCK_TYPE' => 'catalog',
                    'LABEL_PROP_2' => array(
                        0 => 'NEWPRODUCT',
                    ),
                    'LABEL_PROP_MOBILE_2' => '',
                    'LABEL_PROP_POSITION' => 'top-left',
                    'MESS_BTN_ADD_TO_BASKET' => 'В корзину',
                    'MESS_BTN_BUY' => 'Купить',
                    'MESS_BTN_DETAIL' => 'Подробнее',
                    'MESS_BTN_SUBSCRIBE' => 'Подписаться',
                    'MESS_NOT_AVAILABLE' => 'Нет в наличии',
                    'MESS_RELATIVE_QUANTITY_FEW' => 'мало',
                    'MESS_RELATIVE_QUANTITY_MANY' => 'много',
                    'MESS_SHOW_MAX_QUANTITY' => 'Наличие',
                    'OFFER_TREE_PROPS_3' => array(
                        0 => 'COLOR_REF',
                        1 => 'SIZES_SHOES',
                        2 => 'SIZES_CLOTHES',
                    ),
                    'PAGE_ELEMENT_COUNT' => '8',
                    'PARTIAL_PRODUCT_PROPERTIES' => 'N',
                    'PRICE_CODE' => array(
                        0 => 'Цена продажи',
                    ),
                    'PRICE_VAT_INCLUDE' => 'Y',
                    'PRODUCT_BLOCKS_ORDER' => 'price,props,quantityLimit,sku,quantity,buttons,compare',
                    'PRODUCT_ID_VARIABLE' => 'id',
                    'PRODUCT_PROPS_VARIABLE' => 'prop',
                    'PRODUCT_QUANTITY_VARIABLE' => '',
                    'PRODUCT_ROW_VARIANTS' => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
                    'PRODUCT_SUBSCRIPTION' => 'N',
                    'PROPERTY_CODE_2' => array(
                        0 => 'NEWPRODUCT',
                        1 => 'SALELEADER',
                        2 => 'SPECIALOFFER',
                        3 => 'MANUFACTURER',
                        4 => 'MATERIAL',
                        5 => 'COLOR',
                        6 => 'SALELEADER,SPECIALOFFER,MATERIAL,COLOR,KEYWORDS,BRAND_REF',
                    ),
                    'PROPERTY_CODE_3' => array(
                        0 => 'ARTNUMBER',
                        1 => 'COLOR_REF',
                        2 => 'SIZES_SHOES',
                        3 => 'SIZES_CLOTHES',
                    ),
                    'PROPERTY_CODE_MOBILE_2' => '',
                    'RELATIVE_QUANTITY_FACTOR' => '5',
                    'SECTION_CODE' => '',
                    'SECTION_ELEMENT_CODE' => '',
                    'SECTION_ELEMENT_ID' => '',
                    'SECTION_ID' => '',
                    'SHOW_CLOSE_POPUP' => 'N',
                    'SHOW_DISCOUNT_PERCENT' => 'Y',
                    'SHOW_FROM_SECTION' => 'N',
                    'SHOW_MAX_QUANTITY' => 'N',
                    'SHOW_OLD_PRICE' => 'Y',
                    'SHOW_PRICE_COUNT' => '1',
                    'SHOW_PRODUCTS_2' => 'N',
                    'SHOW_SLIDER' => 'Y',
                    'SLIDER_INTERVAL' => '3000',
                    'SLIDER_PROGRESS' => 'Y',
                    'TEMPLATE_THEME' => '',
                    'USE_ENHANCED_ECOMMERCE' => 'N',
                    'USE_PRICE_COUNT' => 'N',
                    'USE_PRODUCT_QUANTITY' => 'Y',
                    'COMPONENT_TEMPLATE' => 'cart-product',
                    'DISPLAY_COMPARE' => 'Y',
                    'PROPERTY_CODE_6' => array(),
                    'PROPERTY_CODE_MOBILE_6' => array(),
                    'CART_PROPERTIES_6' => array(),
                    'ADDITIONAL_PICT_PROP_6' => '-',
                    'LABEL_PROP_6' => array(),
                    'PROPERTY_CODE_7' => array(
                        0 => 'MORE_PHOTO',
                    ),
                    'CART_PROPERTIES_7' => array(),
                    'ADDITIONAL_PICT_PROP_7' => '-',
                    'OFFER_TREE_PROPS_7' => array(),
                    'SHOW_PRODUCTS_6' => 'N',
                    'LABEL_PROP_MOBILE_6' => '',
                    'COMPARE_PATH' => '',
                    'MESS_BTN_COMPARE' => 'Сравнить',
                    'COMPARE_NAME' => 'CATALOG_COMPARE_LIST',
                ),
                false
            ); */ ?>
        </div>
    </div>
</div>
<?php
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>