<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php if ($arResult['ITEMS']): ?>
    <div class="tab-content tab-content--access">
	    <h2>Аксессуары</h2>
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
                    <div class="product-card no-hover">
                        <div class="product-card__img">
                            <img src="<?=$item['images'][0]['src']?>" alt="<?=$item['name']?>">
                        </div>
                        <div class="p-nav-top">
                            <label>
                                <input class="p-nav-top__checkbox" type="checkbox" <?=checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'checked' : '';?>>
                                <div class="p-nav-top__favorite addToFavoriteList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'notActive' : '';?>" data-id='<?=$arPrice['PRODUCT_ID']?>' title="Добавить в избранное"></div>
                            </label>
                            <div title="Добавить в сравнение" class="p-nav-top__list addToCompareList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_COMPARE_ID') ? 'notActive' : 'alt-img';?>" data-id='<?=$arPrice['PRODUCT_ID']?>'></div>
                        </div>
                        <div class="p-nav-middle">
                            <?php if ($item['productPreorder']): ?>
                                <div class="product-label product-label--preorder active">Предзаказ</div>
                            <?php endif; ?>
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
                                <?php if ($item['showOldPrice']): ?>
                                    <div class="p-nav-bottom__oldprice">
                                        <?=number_format($item['oldPrice'], 0, '', ' ')?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <? if (!$item["productPreorder"]): ?>
                                <div
                                        class="p-nav-bottom__shopcart <?= $item['canBuy'] ? 'addToCartBtn' : '' ?>"
                                        data-id='<?= $item['productId'] ?>'
                                    <?= $item['canBuy'] ? 'enable' : 'disable' ?>
                                ></div>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>