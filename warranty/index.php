<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Гарантия");
?>
<div class="container">
    <div class="text-left warranty">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            ".default",
            array(
                "PATH" => "/include/warranty.php",
                "COMPONENT_TEMPLATE" => ".default",
                "AREA_FILE_SHOW" => "file",
                "EDIT_TEMPLATE" => ""
            ),
            false
        );?>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
