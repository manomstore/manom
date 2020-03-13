<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

global $hasSubSec;
$hasSubSec = false;
?>
<?php if (!empty($arResult['SECTIONS'])): ?>
    <?php
    $hasSubSec = true;
    ?>
    <section class='sc-banner'>
        <div class="container">
            <h2 class="sc-banner__h2"><?=$arParams['TITLE']?></h2>
            <?php if (!empty($arResult['BANNER']['img'])): ?>
                <div class="sc-banner__block" style="background-image: url('<?=$arResult['BANNER']['img']?>');">
                    <?php if (!empty($arResult['BANNER']['text'])): ?>
                        <h1 class="sc-banner__title"><?=$arResult['BANNER']['text']?></h1>
                    <?php endif; ?>
                    <?php if (!empty($arResult['BANNER']['btn_link']) && !empty($arResult['BANNER']['btn_text'])): ?>
                        <a href="<?=$arResult['BANNER']['btn_link']?>" class="sc-banner__button">
                            <?=$arResult['BANNER']['btn_text']?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <section class="sc-category">
        <div class="container">
            <div class="sc-category__block">
                <?php foreach ($arResult['SECTIONS'] as $item): ?>
                    <?php
                    if ((int)$item['ID'] === (int)$arParams['DISCOUNTED_SECTION_ID']) {
                        continue;
                    }
                    $img = CFile::ResizeImageGet(
                        $item['PICTURE']['ID'],
                        array('width' => 250, 'height' => 250),
                        BX_RESIZE_IMAGE_PROPORTIONAL,
                        true
                    );
                    ?>
                    <a href="<?=$item['SECTION_PAGE_URL']?>" class="sc-category-card">
                        <div class="sc-category-card__img" style="background: url('<?=$img['src']?>') center no-repeat;"></div>
                        <h3 class="sc-category-card__title"><?=$item['NAME']?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <script>
        $(function () {
            window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS("other")?>);
        });
    </script>
<?php endif; ?>