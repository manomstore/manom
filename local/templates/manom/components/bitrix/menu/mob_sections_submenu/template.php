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


<? foreach ($arResult as $arItem): ?>
    <div class="submenu-mob" data-item-submenu-id='<?= $arItem["ITEM_SUBMENU_ID"] ?>'>
        <button class="submenu-mob__back" type="button" aria-label="Вернуться назад"></button>
        <h2 class="submenu-mob__title"><?= $arItem["TEXT"] ?></h2>
        <ul class="top-nav__list">
            <? foreach ($arItem["PARAMS"]["submenu"] as $children): ?>
                <li>
                    <a class="top-nav__link submenu-mob__link" href="<?= $children["LINK"] ?>"><?= $children["TEXT"] ?></a>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
<? endforeach; ?>
