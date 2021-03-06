<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<?php foreach ($arResult['GROUPED_ITEMS'] as $arrValue): ?>
    <?php if (is_array($arrValue['PROPERTIES']) && count($arrValue['PROPERTIES']) > 0): ?>
        <div class="redesign-characteristics col-12">
            <strong class="name-properties"><?=$arrValue['GROUP']['NAME']?>:</strong>
            <span></span>
            <?php
            $count = 0;
            $secondCol = false;
            ?>
            <?php foreach ($arrValue['PROPERTIES'] as $i => $prop): ?>
                <?php
                $count++;
                ?>
                <div class="row table-padding">
                    <span class="col-40 redesign-characteristics-name"><?=$prop['NAME']?>:</span>
                    <span class="col-60">
                        <?php if (is_array($prop['DISPLAY_VALUE'])): ?>
                            <?=implode('; ', $prop['DISPLAY_VALUE'])?>
                        <?php else: ?>
                            <?=$prop['DISPLAY_VALUE']?>
                        <?php endif; ?>
                    </span>
                </div>
                <?php if ($secondCol === false && (count($arResult['DISPLAY_PROPERTIES']) / 2) <= $count) {
                    $secondCol = true;
                } ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>