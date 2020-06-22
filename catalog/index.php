<?php

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

$APPLICATION->SetTitle('Каталог');

global $catalogFilter;
$catalogFilter = array_merge($catalogFilter, array('>CATALOG_PRICE_1' => 0));

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
?>
<div class='content'>
    <?php $APPLICATION->IncludeComponent(
        'bitrix:catalog',
        'catalog',
        Array(
            'ADD_ELEMENT_CHAIN' => 'Y',
            'ADD_PICT_PROP' => '',
            'ADD_PROPERTIES_TO_BASKET' => 'N',
            'ADD_SECTIONS_CHAIN' => 'Y',
            'AJAX_MODE' => 'N',
            'AJAX_OPTION_ADDITIONAL' => '',
            'AJAX_OPTION_HISTORY' => 'N',
            'AJAX_OPTION_JUMP' => 'N',
            'AJAX_OPTION_STYLE' => 'N',
            'BASKET_URL' => '',
            'BIG_DATA_RCM_TYPE' => '',
            'CACHE_FILTER' => 'N',
            'CACHE_GROUPS' => 'Y',
            'CACHE_TIME' => 36000000,
            'CACHE_TYPE' => 'A',
            'COMMON_ADD_TO_BASKET_ACTION' => '',
            'COMMON_SHOW_CLOSE_POPUP' => 'N',
            'COMPATIBLE_MODE' => 'Y',
            'CONVERT_CURRENCY' => 'N',
            'DETAIL_ADD_DETAIL_TO_SLIDER' => 'N',
            'DETAIL_ADD_TO_BASKET_ACTION' => array(),
            'DETAIL_ADD_TO_BASKET_ACTION_PRIMARY' => array(),
            'DETAIL_BACKGROUND_IMAGE' => '',
            'DETAIL_BRAND_USE' => 'N',
            'DETAIL_BROWSER_TITLE' => '',
            'DETAIL_CHECK_SECTION_ID_VARIABLE' => 'N',
            'DETAIL_DETAIL_PICTURE_MODE' => array(),
            'DETAIL_DISPLAY_NAME' => 'N',
            'DETAIL_DISPLAY_PREVIEW_TEXT_MODE' => '',
            'DETAIL_IMAGE_RESOLUTION' => '',
            'DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE' => array(),
            'DETAIL_MAIN_BLOCK_PROPERTY_CODE' => $properties,
            'DETAIL_META_DESCRIPTION' => '',
            'DETAIL_META_KEYWORDS' => '',
            'DETAIL_OFFERS_FIELD_CODE' => array(),
            'DETAIL_OFFERS_PROPERTY_CODE' => array(),
            'DETAIL_PRODUCT_INFO_BLOCK_ORDER' => '',
            'DETAIL_PRODUCT_PAY_BLOCK_ORDER' => '',
            'DETAIL_PROPERTY_CODE' => $properties,
            'DETAIL_SET_CANONICAL_URL' => 'N',
            'DETAIL_SET_VIEWED_IN_COMPONENT' => 'N',
            'DETAIL_SHOW_POPULAR' => 'N',
            'DETAIL_SHOW_SLIDER' => 'N',
            'DETAIL_SHOW_VIEWED' => 'N',
            'DETAIL_STRICT_SECTION_CHECK' => 'N',
            'DETAIL_USE_COMMENTS' => 'N',
            'DETAIL_USE_VOTE_RATING' => 'N',
            'DISABLE_INIT_JS_IN_COMPONENT' => 'Y',
            'DISPLAY_BOTTOM_PAGER' => 'Y',
            'DISPLAY_TOP_PAGER' => 'N',
            'ELEMENT_SORT_FIELD' => 'sort',
            'ELEMENT_SORT_FIELD2' => 'id',
            'ELEMENT_SORT_ORDER' => 'asc',
            'ELEMENT_SORT_ORDER2' => 'desc',
            'FILE_404' => '',
            'FILTER_HIDE_ON_MOBILE' => 'N',
            'FILTER_VIEW_MODE' => '',
            'GIFTS_DETAIL_BLOCK_TITLE' => '',
            'GIFTS_DETAIL_HIDE_BLOCK_TITLE' => 'N',
            'GIFTS_DETAIL_PAGE_ELEMENT_COUNT' => 0,
            'GIFTS_DETAIL_TEXT_LABEL_GIFT' => '',
            'GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE' => '',
            'GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE' => 'N',
            'GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT' => 0,
            'GIFTS_MESS_BTN_BUY' => '',
            'GIFTS_SECTION_LIST_BLOCK_TITLE' => '',
            'GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE' => 'N',
            'GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT' => 0,
            'GIFTS_SECTION_LIST_TEXT_LABEL_GIFT' => '',
            'GIFTS_SHOW_DISCOUNT_PERCENT' => 'N',
            'GIFTS_SHOW_IMAGE' => 'N',
            'GIFTS_SHOW_NAME' => 'N',
            'GIFTS_SHOW_OLD_PRICE' => 'N',
            'HIDE_NOT_AVAILABLE' => 'Y',
            'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
            'IBLOCK_ID' => 6,
            'IBLOCK_TYPE' => 'catalog',
            'INCLUDE_SUBSECTIONS' => 'Y',
            'INSTANT_RELOAD' => 'N',
            'LABEL_PROP' => array(),
            'LAZY_LOAD' => 'N',
            'LINE_ELEMENT_COUNT' => 0,
            'LINK_ELEMENTS_URL' => '',
            'LINK_IBLOCK_ID' => '',
            'LINK_IBLOCK_TYPE' => '',
            'LINK_PROPERTY_SID' => '',
            'LIST_BROWSER_TITLE' => '',
            'LIST_ENLARGE_PRODUCT' => '',
            'LIST_META_DESCRIPTION' => '',
            'LIST_META_KEYWORDS' => '',
            'LIST_OFFERS_FIELD_CODE' => array(),
            'LIST_OFFERS_LIMIT' => '0',
            'LIST_OFFERS_PROPERTY_CODE' => array(),
            'LIST_PRODUCT_BLOCKS_ORDER' => '',
            'LIST_PRODUCT_ROW_VARIANTS' => '',
            'LIST_PROPERTY_CODE' => $properties,
            'LIST_PROPERTY_CODE_MOBILE' => $properties,
            'LIST_SHOW_SLIDER' => 'N',
            'LIST_SLIDER_INTERVAL' => 0,
            'LIST_SLIDER_PROGRESS' => 'N',
            'LOAD_ON_SCROLL' => 'N',
            'MESSAGE_404' => '',
            'MESS_BTN_ADD_TO_BASKET' => '',
            'MESS_BTN_BUY' => '',
            'MESS_BTN_COMPARE' => '',
            'MESS_BTN_DETAIL' => '',
            'MESS_BTN_SUBSCRIBE' => '',
            'MESS_COMMENTS_TAB' => '',
            'MESS_DESCRIPTION_TAB' => '',
            'MESS_NOT_AVAILABLE' => '',
            'MESS_PRICE_RANGES_TITLE' => '',
            'MESS_PROPERTIES_TAB' => '',
            'OFFERS_CART_PROPERTIES' => array(),
            'OFFERS_SORT_FIELD' => 'sort',
            'OFFERS_SORT_FIELD2' => 'id',
            'OFFERS_SORT_ORDER' => 'asc',
            'OFFERS_SORT_ORDER2' => 'desc',
            'OFFER_ADD_PICT_PROP' => '',
            'OFFER_TREE_PROPS' => array(),
            'PAGER_BASE_LINK_ENABLE' => 'Y',
            'PAGER_DESC_NUMBERING' => 'N',
            'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
            'PAGER_SHOW_ALL' => 'N',
            'PAGER_SHOW_ALWAYS' => 'N',
            'PAGER_TEMPLATE' => 'catalog_section',
            'PAGER_TITLE' => 'Товары',
            'PAGER_PARAMS_NAME' => 'arrPager',
            'PAGE_ELEMENT_COUNT' => 15,
            'PARTIAL_PRODUCT_PROPERTIES' => 'N',
            'PRICE_CODE' => array('Цена продажи', 'РРЦ'),
            'PRICE_VAT_INCLUDE' => 'Y',
            'PRICE_VAT_SHOW_VALUE' => 'N',
            'PRODUCT_DISPLAY_MODE' => 'N',
            'PRODUCT_ID_VARIABLE' => '',
            'PRODUCT_PROPERTIES' => array(),
            'PRODUCT_PROPS_VARIABLE' => '',
            'PRODUCT_QUANTITY_VARIABLE' => '',
            'PRODUCT_SUBSCRIPTION' => 'N',
            'SEARCH_CHECK_DATES' => 'N',
            'SEARCH_NO_WORD_LOGIC' => 'N',
            'SEARCH_PAGE_RESULT_COUNT' => '',
            'SEARCH_RESTART' => 'N',
            'SEARCH_USE_LANGUAGE_GUESS' => 'N',
            'SECTIONS_SHOW_PARENT_NAME' => 'N',
            'SECTIONS_VIEW_MODE' => '',
            'SECTION_ADD_TO_BASKET_ACTION' => '',
            'SECTION_BACKGROUND_IMAGE' => '',
            'SECTION_COUNT_ELEMENTS' => 'N',
            'SECTION_ID_VARIABLE' => '',
            'SECTION_TOP_DEPTH' => 1,
            'SEF_FOLDER' => '/catalog/',
            'SEF_MODE' => 'Y',
            'SEF_URL_TEMPLATES' => Array(
                'compare' => 'compare/',
                'element' => '#SECTION_CODE_PATH#/#ELEMENT_CODE#/',
                'section' => '#SECTION_CODE#/',
                'sections' => '',
                'smart_filter' => '#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/',
            ),
            'SET_LAST_MODIFIED' => 'N',
            'SET_STATUS_404' => 'Y',
            'SET_TITLE' => 'Y',
            'SHOW_404' => 'Y',
            'SHOW_DEACTIVATED' => 'N',
            'SHOW_DISCOUNT_PERCENT' => 'N',
            'SHOW_MAX_QUANTITY' => 'N',
            'SHOW_OLD_PRICE' => 'N',
            'SHOW_PRICE_COUNT' => 1,
            'SHOW_TOP_ELEMENTS' => 'N',
            'SIDEBAR_DETAIL_SHOW' => 'N',
            'SIDEBAR_PATH' => '',
            'SIDEBAR_SECTION_SHOW' => 'N',
            'TEMPLATE_THEME' => '',
            'TOP_ADD_TO_BASKET_ACTION' => '',
            'TOP_ELEMENT_COUNT' => 0,
            'TOP_ELEMENT_SORT_FIELD' => 'sort',
            'TOP_ELEMENT_SORT_FIELD2' => 'id',
            'TOP_ELEMENT_SORT_ORDER' => 'asc',
            'TOP_ELEMENT_SORT_ORDER2' => 'desc',
            'TOP_ENLARGE_PRODUCT' => '',
            'TOP_LINE_ELEMENT_COUNT' => 0,
            'TOP_OFFERS_FIELD_CODE' => array(),
            'TOP_OFFERS_LIMIT' => 0,
            'TOP_OFFERS_PROPERTY_CODE' => array(),
            'TOP_PRODUCT_BLOCKS_ORDER' => '',
            'TOP_PRODUCT_ROW_VARIANTS' => '',
            'TOP_PROPERTY_CODE' => array(),
            'TOP_PROPERTY_CODE_MOBILE' => array(),
            'TOP_SHOW_SLIDER' => 'N',
            'TOP_SLIDER_INTERVAL' => 0,
            'TOP_SLIDER_PROGRESS' => 'N',
            'TOP_VIEW_MODE' => '',
            'USER_CONSENT' => 'N',
            'USER_CONSENT_ID' => 0,
            'USER_CONSENT_IS_CHECKED' => 'N',
            'USER_CONSENT_IS_LOADED' => 'N',
            'USE_BIG_DATA' => 'N',
            'USE_COMMON_SETTINGS_BASKET_POPUP' => 'N',
            'USE_COMPARE' => 'N',
            'USE_ELEMENT_COUNTER' => 'N',
            'USE_ENHANCED_ECOMMERCE' => 'N',
            'USE_FILTER' => 'Y',
            'USE_GIFTS_DETAIL' => 'N',
            'USE_GIFTS_MAIN_PR_SECTION_LIST' => 'N',
            'USE_GIFTS_SECTION' => 'N',
            'USE_MAIN_ELEMENT_SECTION' => 'N',
            'USE_PRICE_COUNT' => 'N',
            'USE_PRODUCT_QUANTITY' => 'N',
            'USE_REVIEW' => 'N',
            'USE_SALE_BESTSELLERS' => 'N',
            'USE_STORE' => 'N',
            'DISCOUNTED_SECTION_ID' => 186,
            'FILTER_NAME' => 'catalogFilter',
            'FILTER_FIELD_CODE' => array(
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
                'DATE_ACTIVE_FROM',
                'ACTIVE_FROM',
                'DATE_ACTIVE_TO',
                'ACTIVE_TO',
                'SHOW_COUNTER',
                'SHOW_COUNTER_START',
                'IBLOCK_TYPE_ID',
                'IBLOCK_ID',
                'IBLOCK_CODE',
                'IBLOCK_NAME',
                'IBLOCK_EXTERNAL_ID',
                'DATE_CREATE',
                'CREATED_BY',
                'CREATED_USER_NAME',
                'TIMESTAMP_X',
                'MODIFIED_BY',
                'USER_NAME',
            ),
            'FILTER_PROPERTY_CODE' => $properties,
            'FILTER_PRICE_CODE' => array('Цена продажи'),
            'FILTER_OFFERS_FIELD_CODE' => array(),
            'FILTER_OFFERS_PROPERTY_CODE' => array(),
        )
    ); ?>
</div>
<?php
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>