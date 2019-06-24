<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("История покупок");
if ($USER->IsAuthorized()){?>
    <div class="container" style="text-align: left;">
        <?$APPLICATION->IncludeComponent("bitrix:sale.personal.order.cancel","",Array(
                "PATH_TO_LIST" => "history.php",
                "PATH_TO_DETAIL" => "order_detail.php?ID=#ID#",
                "ID" => $ID,
                "SET_TITLE" => "Y"
            )
        );?>
    </div>
<?}else{
    LocalRedirect("/");
}?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
