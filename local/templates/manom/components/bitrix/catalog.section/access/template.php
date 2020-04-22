<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php if ($arResult['ITEMS']): ?>
    <div class="tab-content">
        <div class="cb-block">
            <?
            \Manom\GTM::setProductsOnPage($arResult['ITEMS'], true);
            ?>
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <?php
                $class1 = $item['inFavoriteAndCompare'] ? '' : 'notActive';
                $class2 = $item['inFavoriteAndCompare'] ? 'alt-img' : 'notActive';
                ?>
                <div class="cb-block__item col-3">
                    <div class="product-card">
                        <div class="product-card__img">
                            <img src="<?=$item['images'][0]['src']?>" alt="<?=$item['name']?>">
                        </div>
                        <h3 class="p-name">
                            <a href="<?= $item['url'] ?>"
                               data-product-list="recommend"
                               data-product-id="<?= $item['id'] ?>"
                            ><?=$item['name']?></a>
                        </h3>
                        <?php if ($value['PROPERTIES']['ACESS_STR']['VALUE']): ?>
                            <div class="preview-prod1__text">
                                <?php foreach ($value['PROPERTIES']['ACESS_STR']['~VALUE'] as $str): ?>
                                    <p class="preview-prod1__text_item"><?=$str?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($value['PROPERTIES']['BS_STR']['VALUE']): ?>
                            <div class="preview-prod1__text">
                                <?php foreach ($value['PROPERTIES']['BS_STR']['~VALUE'] as $str): ?>
                                    <p class="preview-prod1__text_item"><?=$str?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div class="p-nav-bottom">
                            <div class="p-nav-bottom__price">
                                <?=number_format($item['price'], 0, '', ' ')?>
                                <span> ₽</span>
                                <?php if (
                                    !empty((int)$item['oldPrice']) &&
                                    (int)$item['price'] !== (int)$item['oldPrice']
                                ): ?>
                                    <div class="p-nav-bottom__oldprice">
                                        <?=number_format($item['oldPrice'], 0, '', ' ')?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div
                                    class="p-nav-bottom__shopcart <?=$item['canBuy'] ? 'addToCartBtn' : ''?>"
                                    data-id='<?=$item['productId']?>'
                                <?=$item['canBuy'] ? 'enable' : 'disable'?>
                            ></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>