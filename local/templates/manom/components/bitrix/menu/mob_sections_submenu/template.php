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


<? foreach ($arResult["SECOND_ITEMS"] as $arItem): ?>
    <div class="submenu-mob"
         data-item-second-submenu-id='<?= $arItem["itemId"] ?>'
    >
        <button class="submenu-mob__back" type="button" aria-label="Вернуться назад"></button>
        <h2 class="submenu-mob__title"><?= $arItem["TEXT"] ?></h2>
        <ul class="top-nav__list">
            <? foreach ($arItem["children"] as $children): ?>
                <li class="top-nav__list-item">
                    <a class="top-nav__link" href="<?= $children["url"] ?>"><?= $children["name"] ?></a>
                    <? if (!empty($children["children"])): ?>
                        <span  class="top-nav__link-arrow js-open-sub-submenu" data-item-id="<?= $children["itemId"] ?>"></span>
                    <? endif; ?>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
<? endforeach; ?>

<? foreach ($arResult["THIRD_ITEMS"] as $arItem): ?>
    <div class="submenu-mob2"
         data-item-third-submenu-id='<?= $arItem["itemId"] ?>'
         data-parent-id='<?= $arItem["parentItemId"] ?>'
    >
        <button class="submenu-mob__back2" type="button" aria-label="Вернуться назад"></button>
        <h2 class="submenu-mob__title"><?= $arItem["TEXT"] ?></h2>
        <ul class="top-nav__list">
            <? foreach ($arItem["children"] as $children): ?>
                <li>
                    <a class="top-nav__link submenu-mob__link" href="<?= $children["LINK"] ?>"><?= $children["TEXT"] ?></a>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
<? endforeach; ?>
