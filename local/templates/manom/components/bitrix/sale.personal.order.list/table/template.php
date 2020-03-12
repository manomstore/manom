<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$bNoOrder = true;
?>
<table class="personal" border="0" cellspacing="0" cellpadding="5">
    <tr>
        <td width="25%" rowspan="3" valign="top" class="history-left-menu">
            <aside class="personal__aside">
                <h1 class="personal__title">Личный кабинет</h1>
                <p onclick="location.href = '/user/profile.php'" class="personal-nav__item personal-nav__name" data-id="pb-info">
                    Мои настройки
                </p>
                <p class="personal-nav__name" data-id="pb-info">Настройки:</p>
                <?php if ($_REQUEST['filter_history'] === 'Y'): ?>
                    <a class="personal-nav__item" href="<?=$arResult['CURRENT_PAGE']?>?filter_history=N">
                        <?=GetMessage('STPOL_CUR_ORDERS')?>
                    </a>
                <?php else: ?>
                    <a class="personal-nav__item" href="<?=$arResult['CURRENT_PAGE']?>?filter_history=Y&filter_status=F">
                        <?=GetMessage('STPOL_ORDERS_HISTORY')?>
                    </a>
                <?php endif; ?>
                <p class="personal-nav__name">Покупки:</p>
                <a href="/user/history.php" id="personal-nav__item2" class="personal-nav__item">История покупок</a>
                <a href="/user/favorite/" id="personal-nav__item4" class="personal-nav__item">Товары в избранном</a>
                <a href="/catalog/compare/" id="personal-nav__item4" class="personal-nav__item">Сравнение товаров</a>

                <?php /*
                <p class="personal-nav__name">Моя активность:</p>
                <a href="/user/review/add-list/" id="personal-nav__item4" class="personal-nav__item">Добавить отзыв товару</a>
                <a href="/user/review/" id="personal-nav__item4" class="personal-nav__item">Мои отзывы</a>
                */ ?>
            </aside>
            <div class="attention">
                <?=GetMessage('STPOL_HINT')?>
                <br/>
                <br/>
                <?=GetMessage('STPOL_HINT1')?>
            </div>
        </td>
        <td width="5%" class="helper-history-order" rowspan="3">&nbsp;</td>
        <td width="70%">
            <?
            $products = [];
            foreach ($arResult['ORDER_BY_STATUS'] as $statusOrders) {
                foreach ($statusOrders as $order) {
                    foreach ($order["BASKET_ITEMS"] as $basketItem) {
                        if ((int)$basketItem["PRODUCT_ID"] > 0) {
                            $products[] = $basketItem["PRODUCT_ID"];
                        }
                    }
                }
            }
            ?>
            <?
            GTM::setProductsOnPage($products)
            ?>
            <?php foreach ($arResult['ORDER_BY_STATUS'] as $status => $statusOrders): ?>
                <?php
                $bShowStatus = true;
                ?>
                <?php foreach ($statusOrders as $order): ?>
                    <?php if ($bShowStatus): ?>
                        <h2><?=GetMessage('STPOL_STATUS')?> "<?=$arResult['INFO']['STATUS'][$status]['NAME']?>"</h2>
                        <small><?=$arResult['INFO']['STATUS'][$status]['DESCRIPTION']?></small>
                    <?php endif; ?>
                    <?php
                    $bNoOrder = false;
                    $bShowStatus = false;
                    ?>
                    <table class="sale_personal_order_list">
                        <tr>
                            <td class="padding-table-top">
                                <b>
                                    <?=GetMessage('STPOL_ORDER_NO')?>
                                    <a
                                            class="link-sale_personal_order_list"
                                            title="<?=GetMessage('STPOL_DETAIL_ALT')?>"
                                            href="<?=$order['ORDER']['URL_TO_DETAIL']?>"
                                    >
                                        <?=$order['ORDER']['ACCOUNT_NUMBER']?>
                                    </a>
                                    <?=GetMessage('STPOL_FROM')?>
                                    <?=$order['ORDER']['DATE_INSERT']?>
                                </b>
                                <?php if ($order['ORDER']['CANCELED'] === 'Y'): ?>
                                    <?=GetMessage('STPOL_CANCELED')?>
                                <?php endif; ?>
                                <br/>
                                <b>
                                    <?=GetMessage('STPOL_SUM')?>
                                    <?=$order['ORDER']['FORMATED_PRICE']?>
                                </b>
                                <?php if ($order['ORDER']['PAYED'] === 'Y'): ?>
                                    <?=GetMessage('STPOL_PAYED_Y')?>
                                <?php else: ?>
                                    <?=GetMessage('STPOL_PAYED_N')?>
                                <?php endif; ?>
                                <?php if (IntVal($order['ORDER']['PAY_SYSTEM_ID']) > 0): ?>
                                    <?=GetMessage('P_PAY_SYS')?>
                                    <?=$arResult['INFO']['PAY_SYSTEM'][$order['ORDER']['PAY_SYSTEM_ID']]['NAME']?>
                                <?php endif; ?>
                                <br/>
                                <b><?=GetMessage('STPOL_STATUS_T')?></b>
                                <?=$arResult['INFO']['STATUS'][$order['ORDER']['STATUS_ID']]['NAME']?>
                                <?=GetMessage('STPOL_STATUS_FROM')?>
                                <?=$order['ORDER']['DATE_STATUS']?>
                                <br/>
                                <?php if (IntVal($order['ORDER']['DELIVERY_ID']) > 0): ?>
                                    <b><?=GetMessage('P_DELIVERY')?></b>
                                    <?=$arResult['INFO']['DELIVERY'][$order['ORDER']['DELIVERY_ID']]['NAME']?>
                                <?php elseif (strpos($order['ORDER']['DELIVERY_ID'], ':') !== false): ?>
                                    <b><?=GetMessage('P_DELIVERY')?></b>
                                    <?php
                                    $arId = explode(':', $order['ORDER']['DELIVERY_ID']);
                                    ?>
                                    <?=$arResult['INFO']['DELIVERY_HANDLERS'][$arId[0]]['NAME']?>
                                    (<?=$arResult['INFO']['DELIVERY_HANDLERS'][$arId[0]]['PROFILES'][$arId[1]]['TITLE']?>)
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table class="sale_personal_order_list_table">
                                    <tr>
                                        <td width="0%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td width="100%">
                                            <b><?=GetMessage('STPOL_CONTENT')?></b>
                                        </td>
                                        <td width="0%">&nbsp;</td>
                                    </tr>
                                    <?php foreach ($order['BASKET_ITEMS'] as $item): ?>
                                        <?php
                                        $measure = (isset($vvval['MEASURE_TEXT'])) ?
                                            $item['MEASURE_TEXT'] :
                                            GetMessage('STPOL_SHT');
                                        ?>
                                        <tr>
                                            <td width="0%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td width="100%">
                                                <?php if (strlen($item['DETAIL_PAGE_URL']) > 0): ?>
                                                    <a
                                                            class="link-sale_personal_order_list-alt"
                                                            data-product-list="order"
                                                            data-product-id="<?=$item["PRODUCT_ID"]?>"
                                                            href="<?=$item['DETAIL_PAGE_URL']?>"
                                                    >
                                                <?php endif; ?>
                                                <?=$item['NAME']?>
                                                <?php if (strlen($item['DETAIL_PAGE_URL']) > 0): ?>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                            <td width="0%" nowrap><?=$item['QUANTITY']?> <?=$measure?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="right" class="padding-table-bottom">
                                <a
                                        class="link-sale_personal_order_list"
                                        title="<?=GetMessage('STPOL_DETAIL_ALT')?>"
                                        href="<?=$order['ORDER']['URL_TO_DETAIL']?>"
                                >
                                    <?=GetMessage('STPOL_DETAILS')?>
                                </a>

                                <?php /*
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <a
                                        class="link-sale_personal_order_list"
                                        title="<?=GetMessage('STPOL_REORDER')?>"
                                        href="<?=$order['ORDER']['URL_TO_COPY']?>"
                                >
                                    <?=GetMessage('STPOL_REORDER1')?>
                                </a>
                                */ ?>

                                <?php if ($order['ORDER']['CAN_CANCEL'] === 'Y'): ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a
                                            class="link-sale_personal_order_list"
                                            title="<?=GetMessage('STPOL_CANCEL')?>"
                                            href="<?=$order['ORDER']['URL_TO_CANCEL']?>"
                                    >
                                        <?=GetMessage('STPOL_CANCEL')?>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                    <br/>
                <?php endforeach; ?>
                <br/>
            <?php endforeach; ?>
            <?php if ($bNoOrder): ?>
                <center><?=GetMessage('STPOL_NO_ORDERS')?></center>
            <?php endif; ?>
        </td>
    </tr>
</table>