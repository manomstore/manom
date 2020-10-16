<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$hasFilterElement = false;
foreach ($arResult['ITEMS'] as $item) {
    if (isset($item['PRICE'])) {
        if ($item['VALUES']['MAX']['VALUE'] - $item['VALUES']['MIN']['VALUE'] <= 0) {
            continue;
        }

        $hasFilterElement = true;
    }

    if (isset($item['PRICE']) || !$item['DISPLAY_TYPE'] || !$item['VALUES']) {
        continue;
    }

    $hasFilterElement = true;
}
?>
<?php if ($hasFilterElement): ?>
    <aside class="catalog-filter" data-action="<?=$arResult['FORM_ACTION']?>">
        <input type="hidden" name="set_filter" value="Y">
        <?php foreach ($arResult['HIDDEN'] as $item): ?>
            <input type="hidden" name="<?=$item['CONTROL_NAME']?>" id="<?=$item['CONTROL_ID']?>" value="<?=$item['HTML_VALUE']?>"/>
        <?php endforeach; ?>
        <div class="catalog-filter__close"></div>
        <ul class="catalog-filter__ul">
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <?php if (isset($item['PRICE'])): ?>
                    <?php
                    if ($item['VALUES']['MAX']['VALUE'] - $item['VALUES']['MIN']['VALUE'] <= 0) {
                        continue;
                    }
                    $precision = $item['DECIMALS'] ?: 0;
                    $minVal = $item['VALUES']['MIN']['VALUE'];
                    $maxVal = $item['VALUES']['MAX']['VALUE'];
                    $minVal = $minVal > 0 ? $minVal : 1;
                    $maxVal = $maxVal > 0 ? $maxVal : 1;
                    $checked = !empty($item['VALUES']['MIN']['HTML_VALUE']) || !empty($item['VALUES']['MIN']['HTML_VALUE']);
                    ?>
                    <li class="catalog-filter__li">
                        <input type="checkbox" class="checkbox-1">
                        <i></i>
                        <h3>Стоимость</h3>
                        <p class="price-slider">
                            <label>
                                <input
                                        class="catalog-filter__checkbox catalogPrice"
                                        type="checkbox"
                                    <?= $checked ? "checked" : "" ?>
                                        data-name-min="<?= $item['VALUES']['MIN']['CONTROL_NAME'] ?>"
                                        data-name-max="<?= $item['VALUES']['MAX']['CONTROL_NAME'] ?>"
                                        data-title="Стоимость: "
                                        name="<?= $item['VALUES']['MIN']['CONTROL_NAME'] ?><?= $item['VALUES']['MAX']['CONTROL_NAME'] ?>"
                                >
                                <span class="catalog-filter__item"> ₽</span>
                            </label>
                            <input
                                    class="form-control"
                                    type="number"
                                    step="1000"
                                    min="<?= number_format($minVal, $precision, '.', '') ?>"
                                    max="<?= number_format($maxVal, $precision, '.', '') ?>"
                                    data-name="<?= $item['VALUES']['MIN']['CONTROL_NAME'] ?><?= $item['VALUES']['MAX']['CONTROL_NAME'] ?>"
                                    name="<?= $item['VALUES']['MIN']['CONTROL_NAME'] ?>"
                                    id="price-start-alt"
                                <? if ($item['VALUES']['MIN']['HTML_VALUE']): ?>
                                    value="<?= $item['VALUES']['MIN']['HTML_VALUE'] ?>"
                                <? endif; ?>

                            > &mdash;
                            <input
                                    class="form-control"
                                    type="number"
                                    step="1000"
                                    min="<?= number_format($minVal, $precision, '.', '') ?>"
                                    max="<?= number_format($maxVal, $precision, '.', '') ?>"
                                    data-name="<?= $item['VALUES']['MIN']['CONTROL_NAME'] ?><?= $item['VALUES']['MAX']['CONTROL_NAME'] ?>"
                                    name="<?= $item['VALUES']['MAX']['CONTROL_NAME'] ?>"
                                    id="price-end-alt"
                                <? if ($item['VALUES']['MAX']['HTML_VALUE']): ?>
                                    value="<?= $item['VALUES']['MAX']['HTML_VALUE'] ?>"
                                <? endif; ?>
                            >
                            ₽
                            <span id="slider-range-alt"></span>
                        </p>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <?php if (isset($item['PRICE']) || !$item['DISPLAY_TYPE'] || count((array)$item['VALUES']) <= 1) {
                    continue;
                } ?>
                <li class="catalog-filter__li">
                    <input type="checkbox" class="checkbox-1">
                    <i></i>
                    <h3><?=$item['NAME']?></h3>
                    <?php foreach ($item['VALUES'] as $value): ?>
                        <p>
                            <label>
                                <input
                                    class="catalog-filter__checkbox <?= $value["DISABLED"] ? 'disabled' : '' ?>"
                                    type="checkbox"
                                    <?= $value["DISABLED"] ? 'disabled' : '' ?>
                                    name="<?=$value['CONTROL_NAME']?>"
                                    id="<?=$value['CONTROL_ID']?>"
                                    value="<?=$value['HTML_VALUE']?>"
                                    data-title="<?=$item['NAME']?>: "
                                    data-value="<?=$value['VALUE']?>"
                                    <?=$value['CHECKED'] ? 'checked="checked"' : ''?>>
                                <span class="catalog-filter__item"><?=$value['VALUE']?></span>
                            </label>
                        </p>
                    <?php endforeach; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </aside>
<?php endif; ?>