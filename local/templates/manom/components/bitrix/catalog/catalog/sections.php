<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:breadcrumb',
    'articles',
    array(
        'START_FROM' => 0,
        'PATH' => '',
        'SITE_ID' => 's1',
    ),
    false
); ?>
<?php if (empty($_REQUEST['brand'])): ?>
    <?php $APPLICATION->IncludeComponent(
        'bitrix:catalog.section.list',
        '',
        array(
            'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'CACHE_TYPE' => $arParams['CACHE_TYPE'],
            'CACHE_TIME' => $arParams['CACHE_TIME'],
            'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
            'COUNT_ELEMENTS' => $arParams['SECTION_COUNT_ELEMENTS'],
            'TOP_DEPTH' => $arParams['SECTION_TOP_DEPTH'],
            'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
            'VIEW_MODE' => $arParams['SECTIONS_VIEW_MODE'],
            'SHOW_PARENT_NAME' => $arParams['SECTIONS_SHOW_PARENT_NAME'],
            'HIDE_SECTION_NAME' => $arParams['SECTIONS_HIDE_SECTION_NAME'] ?? 'N',
            'ADD_SECTIONS_CHAIN' => $arParams['ADD_SECTIONS_CHAIN'] ?? '',
            'DISCOUNTED_SECTION_ID' => $arParams['DISCOUNTED_SECTION_ID'],
            'TITLE' => $APPLICATION->GetTitle()
        ),
        $component,
        ($arParams['SHOW_TOP_ELEMENTS'] !== 'N' ? array('HIDE_ICONS' => 'Y') : array())
    ); ?>
<?php else: ?>
    <main class="catalog container">
        <div class="catalog-main">
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

            <section class="catalog-block" style="width:100%;">
                <h2 class="cb-title"></h2>
                <p style="padding: 20px 0;">Товар по данному запросу отсутствует.</p>
            </section>
        </div>
    </main>
<?php endif; ?>
<script>
    $(function () {
        window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS("other")?>);
    });
</script>
