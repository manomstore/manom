<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?>
<div class="container">
    <?$APPLICATION->IncludeComponent(
        "bitrix:system.auth.forgotpasswd",
        "manom",
        array()
    );?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>