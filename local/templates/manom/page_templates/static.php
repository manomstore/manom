<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("");
$filename = str_replace(["/static/", ".php"], ["", ""], $_SERVER["REAL_FILE_PATH"]);

?>
    <div class="content">
        <div class="container">
            <div class="text-content text-content--container">
                <?
                $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    ".default",
                    array(
                        "PATH" => "/include/static_" . $filename . ".php",
                        "COMPONENT_TEMPLATE" => ".default",
                        "AREA_FILE_SHOW" => "file",
                        "EDIT_TEMPLATE" => ""
                    ),
                    false
                ); ?>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            window.gtmActions.initCommonData(<?=\Manom\GTM::getDataJS("info")?>);
        });
    </script>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>