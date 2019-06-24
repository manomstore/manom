<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "Manom");
$APPLICATION->SetPageProperty("description", "Manom");
$APPLICATION->SetPageProperty("title", "Manom");
$APPLICATION->SetTitle("О магазине");
?>
<div class="container">
    <div class="text-left">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.include",
            ".default",
            array(
                "PATH" => "/include/about.php",
                "COMPONENT_TEMPLATE" => ".default",
                "AREA_FILE_SHOW" => "file",
                "EDIT_TEMPLATE" => ""
            ),
            false
        );?>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>