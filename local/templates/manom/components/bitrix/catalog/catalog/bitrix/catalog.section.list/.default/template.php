<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

?>
<?php if (!empty($arResult['SECTIONS'])): ?>
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

<?php endif; ?>