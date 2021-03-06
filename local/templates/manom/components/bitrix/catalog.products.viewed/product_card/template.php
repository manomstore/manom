<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<? if (!empty($arResult["ITEMS"])): ?>
    <div class="watched">
        <h2 class="watched__title">Недавно просмотренные товары</h2>
        <div class="watched__slider swiper-container">
            <div class="swiper-wrapper">
                <? foreach ($arResult["ITEMS"] as $item): ?>
                    <div class="watched__slide swiper-slide">
                        <a href="<?= $item["url"] ?>">
                            <? if (!empty($item["previewPicture"])): ?>
                                <img src="<?= $item["previewPicture"]["src"] ?>" alt="<?= $item["name"] ?>">
                            <? endif; ?>
                            <div class="watched__label">
                                <?php if ($item['productPreorder']): ?>
                                    <div class="product-label product-label--preorder active">Предзаказ</div>
                                <?php endif; ?>
                            </div>
                            <div class="watched__price">
                                <?= number_format($item['price'], 0, '', ' ') ?>
                                <span>&nbsp;₽</span>
                                <?php if ($item['showOldPrice']): ?>
                                    <div class="watched__oldprice">
                                        <?= number_format($item['oldPrice'], 0, '', ' ') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p><?= $item["name"] ?></p>
                        </a>
                    </div>
                <? endforeach; ?>
            </div>


        </div>
        <div class="swiper-button-prev watched__button watched__button-prev visually-hidden"></div>
        <div class="swiper-button-next watched__button watched__button-next visually-hidden"></div>
    </div>
<? endif; ?>