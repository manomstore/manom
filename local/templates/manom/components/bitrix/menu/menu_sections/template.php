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
                                <ul class="main-nav__sublist">
                                    <span class="main-nav__sublist-header">Категории</span>
                                    <? foreach ($arItem["CHILDREN"] as $children): ?>
                                        <li>
                                            <a class="main-nav__link main-nav__link--lv2" href="<?= $children["LINK"] ?>">
                                                <?= $children["TEXT"] ?>
                                            </a>
                                        </li>
                                    <? endforeach; ?>
                                </ul>
                                <ul class="main-nav__sublist main-nav__sublist--brands">
                                    <span class="main-nav__sublist-header">Бренды</span>
                                    <li>
                                        <a class="" href="#">
                                            <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/brand-apple.svg" alt="apple">
                                        </a>
                                    </li>
                                    <li>
                                        <a class="" href="#">
                                            <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/brand-lg.svg" alt="lg">
                                        </a>
                                    </li>
                                    <li>
                                        <a class="" href="#">
                                            <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/brand-philips.svg" alt="philips">
                                        </a>
                                    </li>
                                    <li>
                                        <a class="" href="#">
                                            <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/brand-xiaomi.svg" alt="xiaomi">
                                        </a>
                                    </li>
                                    <li>
                                        <a class="" href="#">
                                            <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/brand-honor.svg" alt="honor">
                                        </a>
                                    </li>
                                    <li>
                                        <a class="" href="#">
                                            <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/brand-samsung.svg" alt="samsung">
                                        </a>
                                    </li>
                                    <li>
                                        <a class="" href="#">
                                            <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/icons/brand-dell.svg" alt="dell">
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <? endif; ?>
                </li>
            <? endforeach; ?>
        </ul>
    </div>
<? endif; ?>