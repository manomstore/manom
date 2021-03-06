	<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
use Manom\Store\StoreList;

if(!empty($arResult["DELIVERY"]))
{
	?>
	<b><?=GetMessage("SOA_TEMPL_DELIVERY")?></b>
    <table class="sale_order_full_table delivery-block">
        <?
        foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery):?>
            <tr>
                <td valign="top" width="0%">
                    <input type="radio" id="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>"
                           name="<?= htmlspecialcharsbx($arDelivery["FIELD_NAME"]) ?>"
                           value="<?= $arDelivery["ID"] ?>"<?
                    if ($arDelivery["CHECKED"] == "Y") {
                        echo " checked";
                    } ?> onclick="submitForm();"/>
                </td>
                <td valign="top" width="100%">
                    <label for="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>"
                           class="<?= !empty($arDelivery["STORE"]) ? "is-shop" : "" ?>">
                        <b><?= htmlspecialcharsbx($arDelivery["NAME"]) ?></b><br/>
                        <?
                        if (strlen($arDelivery["PERIOD_TEXT"]) > 0) {
                            echo "<span class='so_delivery_period'>" . $arDelivery["PERIOD_TEXT"] . "</span>";
                            ?><br/><?
                        }
                        ?>
                        <?= GetMessage("SALE_DELIV_PRICE"); ?> <span
                                class="prs_soa"><?= $arDelivery["PRICE_FORMATED"] ?></span><br/>
                        <?
                            ?>
                        <? if (strlen($arDelivery["DESCRIPTION"]) > 0): ?>
                            <span class="dsc_soa"><?= $arDelivery["DESCRIPTION"] ?></span><br/>
                        <? endif; ?>
                        <? if (
                            !empty($arDelivery["STORE"])
                            && ($store = $arResult['STORE_LIST'][$arDelivery["STORE"][0]])
                        ): ?>
                            <span class="address_soa"><?= $store["ADDRESS"] ?></span><br/>
                            <span class="schedule_soa"><?= $store["SCHEDULE"] ?></span><br/>
                        <? endif; ?>
                        <span class="js-shop-schedule"><?= StoreList::getInstance()->getShop()->getSchedule()?></span>
                    </label>
                </td>
            </tr>
        <?endforeach; ?>
    </table>
	<br /><br />
	<?
}
?>
