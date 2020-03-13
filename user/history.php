<?php

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

$APPLICATION->SetTitle('История покупок');

if (!$USER->IsAuthorized()) {
    LocalRedirect('/');
}
?>
<div class="content">
    <?php if ($USER->IsAuthorized()) : ?>
        <div class="container">
            <section class="personal-burger" style="margin-top: 20px;">
                <div class="container">
                    <input class="filter-burger__checkbox" type="checkbox" id="filter-burger">
                    <label class="filter-burger" for="filter-burger" title="Фильтр"></label>
                </div>
            </section>
            <section id="pb-history" class="personal-block__section">
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:sale.personal.order.list',
                    'table',
                    Array(
                        'STATUS_COLOR_N' => 'green',
                        'STATUS_COLOR_P' => 'yellow',
                        'STATUS_COLOR_F' => 'gray',
                        'STATUS_COLOR_PSEUDO_CANCELLED' => 'red',
                        'PATH_TO_DETAIL' => 'order_detail.php?ID=#ID#',
                        'PATH_TO_COPY' => 'basket.php',
                        'PATH_TO_CANCEL' => 'order_cancel.php?ID=#ID#',
                        'PATH_TO_BASKET' => '/cart/',
                        'PATH_TO_PAYMENT' => 'payment.php',
                        'ORDERS_PER_PAGE' => '20',
                        'ID' => $ID,
                        'SET_TITLE' => 'Y',
                        'SAVE_IN_SESSION' => 'Y',
                        'NAV_TEMPLATE' => '',
                        'CACHE_TYPE' => 'A',
                        'CACHE_TIME' => '3600',
                        'CACHE_GROUPS' => 'Y',
                        'HISTORIC_STATUSES' => array(
                            0 => 'F',
                        ),
                        'ACTIVE_DATE_FORMAT' => 'd.m.Y',
                        'COMPONENT_TEMPLATE' => 'list',
                        'PATH_TO_CATALOG' => '/catalog/',
                        'RESTRICT_CHANGE_PAYSYSTEM' => array(
                            0 => '0',
                        ),
                        'REFRESH_PRICES' => 'N',
                        'DEFAULT_SORT' => 'STATUS',
                        'STATUS_COLOR_DS' => 'gray',
                    ),
                    false
                ); ?>
            </section>
        </div>
    <?php endif; ?>
</div>
    <script>
        $(function () {
            window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS("other")?>);
        });
    </script>
<?php
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>