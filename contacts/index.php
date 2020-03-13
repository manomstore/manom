<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Manom");
$APPLICATION->SetPageProperty("description", "Manom");
$APPLICATION->SetPageProperty("title", "Manom");
$APPLICATION->SetTitle("Контакты");
?>
<div class="content"></div>
    <div class="container">
        <div class="text-content text-content--container">
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                ".default",
                array(
                    "PATH" => "/include/contacts.php",
                    "COMPONENT_TEMPLATE" => ".default",
                    "AREA_FILE_SHOW" => "file",
                    "EDIT_TEMPLATE" => ""
                ),
                false
            );?>
        </div>
    </div>
</div>
<script>
    $(function() {
        window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS("info")?>);
    });
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
