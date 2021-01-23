<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php if ($arResult['ITEMS']): ?>
    <div class="tab-content tab-content--access">
        <h2>Услуги</h2>
        <div class="cb-block">
            <?
            \Manom\GTM::setProductsOnPage($arResult['ITEMS'], true);
            ?>
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <?php
                ?>
                <div class="cb-block__item col-3">
                    <div class="product-card no-hover">
                        <div class="product-card__img">
                            <img src="<?= $item['images'][0]['src'] ?>" alt="<?= $item['name'] ?>" style="cursor:default;">
                        </div>
                        <h3 class="p-name no-link" style="cursor: default">
                            <span data-product-list="recommend"
                               data-product-id="<?= $item['id'] ?>"
                            ><?= $item['name'] ?></span>
                        </h3>
                        <div class="p-nav-bottom">
                            <div class="p-nav-bottom__price">
                                <? if ($item['price'] > 0): ?>
                                    <?= number_format($item['price'], 0, '', ' ') ?>
                                    <span> ₽</span>
                                <? else: ?>
                                    Бесплатно
                                <? endif; ?>
                            </div>
                            <div
                                    class="p-nav-bottom__shopcart addToCartBtn"
                                    data-id='<?= $item['productId'] ?>' enable
                            ></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>