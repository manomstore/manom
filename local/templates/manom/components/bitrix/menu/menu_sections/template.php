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
                                <div class="main-nav__sublist">
                                    <? if (!empty($arItem['BRANDS'])): ?>
                                        <span class="main-nav__sublist-header">Категории</span>
                                    <? endif; ?>
                                    <div class="main-nav__list-pack">
                                        <? foreach ($arItem["CHILDREN"] as $childrenChunk): ?>
                                            <ul>
                                                <? foreach ($childrenChunk as $children): ?>
                                                    <li class="<?= $children["DISABLED"] ? "disabled" : "" ?>">
                                                        <a class="main-nav__link main-nav__link--lv2"
                                                           href="<?= $children["LINK"] ?>">
                                                            <?= $children["TEXT"] ?>
                                                        </a>
                                                    </li>
                                                <? endforeach; ?>
                                            </ul>
                                        <? endforeach; ?>
                                    </div>
                                </div>
                                <? if (!empty($arItem['BRANDS'])): ?>
                                    <div class="main-nav__sublist main-nav__sublist--brands">
                                        <span class="main-nav__sublist-header">Бренды</span>
                                        <div class="main-nav__list-pack">
                                            <? foreach ($arItem['BRANDS'] as $brandsChunk): ?>
                                                <ul>
                                                    <? foreach ($brandsChunk as $brand): ?>
                                                        <li>
                                                            <a href="/catalog/<?= $arItem["CODE"] ?>/brand/<?= $brand["code"] ?>/">
                                                                <img src="<?= $brand["logo"] ?>"
                                                                     alt="<?= $brand["name"] ?>">
                                                            </a>
                                                        </li>
                                                    <? endforeach; ?>
                                                </ul>
                                            <? endforeach; ?>
                                        </div>
                                    </div>
                                <? endif; ?>
                            </div>
                        </div>
                    <? endif; ?>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
<? endif; ?>