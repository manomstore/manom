<?php

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

$APPLICATION->SetTitle('История покупок');

if (!$USER->IsAuthorized()) {
    LocalRedirect('/');
}
?>
<div class="content">
    <?php if ($USER->IsAuthorized()): ?>
        <div class="container">
            <?php $APPLICATION->IncludeComponent(
                'bitrix:sale.personal.order.detail',
                'bootstrap_v4',
                array(
                    'PATH_TO_LIST' => 'history.php',
                    'PATH_TO_CANCEL' => 'order_cancel.php',
                    'PATH_TO_PAYMENT' => 'payment.php',
                    'PATH_TO_COPY' => '',
                    'ID' => $ID,
                    'CACHE_TYPE' => 'A',
                    'CACHE_TIME' => '3600',
                    'CACHE_GROUPS' => 'Y',
                    'SET_TITLE' => 'Y',
                    'ACTIVE_DATE_FORMAT' => 'd.m.Y',
                    'PICTURE_WIDTH' => '110',
                    'PICTURE_HEIGHT' => '110',
                    'PICTURE_RESAMPLE_TYPE' => '1',
                    'CUSTOM_SELECT_PROPS' => array(),
                    'PROP_1' => array(),
                    'PROP_2' => array(),
                    'COMPONENT_TEMPLATE' => 'bootstrap_v4',
                    'RESTRICT_CHANGE_PAYSYSTEM' => array(
                        0 => '0',
                    ),
                    'REFRESH_PRICES' => 'Y',
                ),
                false
            ); ?>
            <script>
                $(function () {
                    window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS("other")?>);
                });
            </script>
        </div>
    <?php endif; ?>
</div>
<?php
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>