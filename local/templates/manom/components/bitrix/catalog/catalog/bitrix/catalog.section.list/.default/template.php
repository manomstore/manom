<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

?>
<div  class="catalog-filter__category-cover">
    <ul>
        <?php if (!empty($arResult['SECTIONS'])): ?>
            <? if (!empty($arResult["SECTION"]["NAME"])): ?>
                <h3><?= $arResult["SECTION"]["NAME"] ?></h3>
            <? endif; ?>
            <li class="catalog-filter__category">

                <? foreach ($arResult["SECTIONS"] as $section): ?>
                    <p>
                        <a class="catalog-filter__category-link" href="<?= $section["SECTION_PAGE_URL"] ?>">
                            <?= $section["NAME"] ?>
                        </a>
                    </p>
                <? endforeach; ?>
            </li>
        <? endif; ?>
    </ul>
</div>
