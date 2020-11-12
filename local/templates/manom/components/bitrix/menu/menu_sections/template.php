<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true); ?>

<? if (!empty($arResult)): ?>
    <div class="main-nav">
        <ul class="main-nav__list">
            <? foreach ($arResult as $arItem): ?>
                <li class="main-nav__item main-nav__item--dropdown">
                    <a class="main-nav__link" href="<?= $arItem["LINK"] ?>">
                        <?= $arItem["TEXT"] ?>
                        <? if (!empty($arItem["CHILDREN"])): ?>
                            <svg class="main-nav__icon main-nav__icon--rotate" viewBox="0 0 10 6" width="10" height="7"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 1L5 5 1 1" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        <? endif; ?>
                    </a>
                    <? if (!empty($arItem["CHILDREN"])): ?>
                        <div class="main-nav__list-wrapper">
                            <div class="main-nav__inner">
                                <div  class="main-nav__sublist">
                                    <span class="main-nav__sublist-header">Категории</span>
                                    <ul>
                                        <? foreach ($arItem["CHILDREN"] as $children): ?>
                                            <li>
                                                <a class="main-nav__link main-nav__link--lv2" href="<?= $children["LINK"] ?>">
                                                    <?= $children["TEXT"] ?>
                                                </a>
                                            </li>
                                        <? endforeach; ?>
                                    </ul>
                                <? if (!empty($arItem['BRANDS'])): ?>
                                    <ul class="main-nav__sublist main-nav__sublist--brands">
                                        <span class="main-nav__sublist-header">Бренды</span>
                                        <? foreach ($arItem['BRANDS'] as $brand): ?>
                                            <li>
                                                <a href="/catalog/<?= $arItem["CODE"] ?>/brand/<?= $brand["code"] ?>/">
                                                    <img src="<?= $brand["logo"] ?>" alt="<?= $brand["name"] ?>">
                                                </a>
                                            </li>
                                        <? endforeach; ?>
                                    </ul>
                                <? endif; ?>
                            </div>
                        </div>
                    <? endif; ?>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
<? endif; ?>