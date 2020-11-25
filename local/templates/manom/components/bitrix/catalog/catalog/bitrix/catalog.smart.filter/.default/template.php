<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
?>
<?php if ($arResult["HAS_FILTER_ELEMENT"]): ?>
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
                    $priceCheckboxName = $item['VALUES']['MIN']['CONTROL_NAME'] . $item['VALUES']['MAX']['CONTROL_NAME'];

                    $rangeSize = $maxVal - $minVal;
                    $stepSize = 0;
                    if ($rangeSize > 1) {
                        $percent = 0;

                        for ($i = 1; $i < $rangeSize; $i++) {
                            if ($rangeSize % $i === 0) {
                                $percent = ($i / $rangeSize) * 100;
                                if ($percent <= 5) {
                                    $stepSize = $i;
                                } else {
                                    break;
                                }
                            }
                        }
                    }

                    if (!$stepSize) {
                        $stepSize = 1000;
                    }

                    $checked = !empty($request->get($priceCheckboxName));
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
                                        name="<?= $priceCheckboxName ?>"
                                >
                                <span class="catalog-filter__item"> ₽</span>
                            </label>
                            <input
                                    class="form-control"
                                    type="number"
                                    step="<?= $stepSize ?>"
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
                                    step="<?= $stepSize ?>"
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

                <? if ($item["CODE"] === "color"): ?>
                    <li class="catalog-filter__li">
                        <input type="checkbox" class="checkbox-1">
                        <i></i>
                        <h3><?= $item['NAME'] ?></h3>
                        <div class="catalog-filter__color">
                            <?php foreach ($item['VALUES'] as $value): ?>
                                <p>
                                    <input
                                            class="catalog-filter__checkbox <?= $value["DISABLED"] ? 'disabled' : '' ?>"
                                            name="<?= $value['CONTROL_NAME'] ?>"
                                            type="checkbox"
                                        <?= $value["DISABLED"] ? 'disabled' : '' ?>
                                            id="<?= $value['CONTROL_ID'] ?>"
                                            value="<?= $value['HTML_VALUE'] ?>"
                                            data-title="<?= $item['NAME'] ?>: "
                                            data-value="<?= $value['name'] ?>"
                                        <?= $value['CHECKED'] ? 'checked="checked"' : '' ?>
                                    >
                                    <label for="<?= $value['CONTROL_ID'] ?>"
                                           class="product-content__color-<?= $value['code'] ?>"
                                           style="  background-color: <?= $value['value'] ?>; border-color: <?= $value['value'] ?>;"
                                           title="<?= $value['name'] ?>"></label>
                                </p>
                            <? endforeach; ?>
                        </div>
                    </li>
                <? else: ?>
                    <li class="catalog-filter__li">
                    <input type="checkbox" class="checkbox-1">
                    <i></i>
                    <h3><?=$item['NAME']?></h3>
                    <?php foreach ($item['VALUES'] as $value): ?>
                        <p class="<?= $value["SHOW"] ? "top" : "" ?> catalog-filter__list-item">
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
                    <? if ($item["SHOW_MORE"]): ?>
                        <button type="button" class="toggle-button">
                            <span class="show-all">Показать все</span>
                            <span class="fold">Свернуть</span>
                        </button>
                    <? endif; ?>
                </li>
                <? endif; ?>
            <?php endforeach; ?>
        </ul>
    </aside>
<?php endif; ?>