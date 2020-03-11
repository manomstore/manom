<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?>
<div class="content">
    <div class="container">
        <?$APPLICATION->IncludeComponent(
            "bitrix:system.auth.forgotpasswd",
            "manom",
            array()
        );?>
        <script>
            $(function () {
                window.gtmActions.initCommonData(<?=GTM::getDataJS("other")?>);
            });
        </script>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>