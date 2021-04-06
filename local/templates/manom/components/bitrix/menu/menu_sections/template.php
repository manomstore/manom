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
                <li class="main-nav__item main-nav__item--dropdown <?= $arItem["disabled"] ? "disabled" : "" ?>">
                    <a class="main-nav__link" <?= $arItem["notLink"] ? "onclick='return false;'" : "" ?>
                       href="<?= $arItem["LINK"] ?>">
                        <?= $arItem["TEXT"] ?>
                        <? if (!empty($arItem["PARAMS"]["submenu"])): ?>
                            <svg class="main-nav__icon main-nav__icon--rotate" viewBox="0 0 10 6" width="10" height="7"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 1L5 5 1 1" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        <? endif; ?>
                    </a>
                    <? if (!empty($arItem["PARAMS"]["submenu"])): ?>
                        <? switch ($arItem["PARAMS"]["type"]):
                            case "sections":
                                ?>
                                <!-- Подменю для пунктов "Каталог товаров" и "Акции"-->
                                <div class="main-nav__list-wrapper">
                                    <div class="main-nav__inner">
                                        <div class="main-nav__sublist">
                                            <div class="main-nav__list-pack">
                                                <ul>
                                                    <? foreach ($arItem["PARAMS"]["submenu"] as $submenuItem): ?>
                                                        <li>
                                                            <a class="main-nav__link main-nav__link--lv2"
                                                               href="<?= $submenuItem["url"] ?>">
                                                                <?= $submenuItem["name"] ?>
                                                            </a>
                                                            <? if (!empty($submenuItem["children"])): ?>
                                                                <!-- Вывод третьего уровня-->
                                                                <div class="main-nav__list-wrapper">
                                                                    <div class="main-nav__inner">
                                                                        <div class="main-nav__sublist">
                                                                            <div class="main-nav__list-pack">
                                                                                <ul>
                                                                                    <? foreach ($submenuItem["children"] as $children): ?>
                                                                                        <li>
                                                                                            <a class="main-nav__link main-nav__link--lv3"
                                                                                               href="<?= $children["url"] ?>">
                                                                                                <?= $children["name"] ?>
                                                                                            </a>
                                                                                        </li>
                                                                                    <? endforeach; ?>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <!-- /Вывод третьего уровня-->
                                                            <? endif; ?>
                                                        </li>
                                                    <? endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Подменю для пунктов "Каталог товаров" и "Акции"-->
                                <? break; ?>
                            <? case "brands": ?>
                                <!-- Подменю для пункта "Бренды"-->
                                <div class="main-nav__list-wrapper">
                                    <div class="main-nav__inner">
                                        <div class="main-nav__sublist">
                                            <div class="main-nav__list-pack">
                                                <ul>
                                                    <? foreach ($arItem["PARAMS"]["submenu"] as $submenuItem): ?>
                                                        <li>
                                                            <a class="main-nav__link main-nav__link--lv2"
                                                               href="<?= $submenuItem["url"] ?>">
                                                                <?= $submenuItem["name"] ?>
                                                            </a>
                                                            <img style="width: 50px; height: 50px"
                                                                 src="<?= $submenuItem["logo"] ?>"
                                                                 alt="<?= $submenuItem["name"] ?>">
                                                        </li>
                                                    <? endforeach; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Подменю для пункта "Бренды"-->
                                <? break; ?>
                            <? endswitch; ?>
                    <? endif; ?>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
<? endif; ?>