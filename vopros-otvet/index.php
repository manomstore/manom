<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("vopros-otvet");
?><h4>Могу ли проверить товар перед покупкой и как это будет происходить?</h4>
    <p>
        Вы сможете проверить товар, при курьере в течении 15 минут, строго после оплаты.&nbsp; Если при проверке
        обнаружите заводской брак, возврат денег будет произведен на месте.</p>
    <script>
        $(function () {
            window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS("info")?>);
        });
    </script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>