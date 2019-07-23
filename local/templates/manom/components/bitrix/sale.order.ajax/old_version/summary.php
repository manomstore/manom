<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<b><?=GetMessage("SOA_TEMPL_SUM_TITLE")?></b><br />

<? $colspan = count($arResult["GRID"]["HEADERS"]) - 1; ?>

<table class="sale_order_full">
	<thead>
		<tr>
		<?foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn):?>
			<th><?=$arColumn["name"]?></th>
		<?endforeach;?>
		</tr>
	</thead>
	<tbody>
		<?foreach ($arResult["GRID"]["ROWS"] as $k => $arData):?>
		<tr>
			<?foreach ($arResult["GRID"]["HEADERS"] as $id => $arColumn):
                $isSumCol = false;
                if ($arColumn["id"] === "SUM") {
                    $sumBase = isset($arData["columns"]["SUM_BASE"]) ?
                        $arData["columns"]["SUM_BASE"] : $arData["data"]["SUM_BASE"];
                    $sumBase = \CCurrencyLang::CurrencyFormat(
                        $sumBase,
                        isset($arData["columns"]["CURRENCY"]) ?
                            $arData["columns"]["CURRENCY"] : $arData["data"]["CURRENCY"],
                        false
                    );
                    $existDiscount = (int)(isset($arData["columns"]["DISCOUNT_PRICE_PERCENT"]) ?
                            $arData["columns"]["CURRENCY"] : $arData["data"]["DISCOUNT_PRICE_PERCENT"]) > 0;
                    $isSumCol = true;
                }
				$align = (isset($arColumn["align"])) ? "align=".$arColumn["align"] : "";
			?>
                <td <?= $align ?>
                    <? if ($isSumCol):?>
                        data-old-price="<?= !empty($sumBase) ? $sumBase : "" ?>"
                        data-exist-discount="<?= $existDiscount ?>"
                    <? endif; ?>>
                    <?
                    if (isset($arData["columns"][$arColumn["id"]])) {
                        echo $arData["columns"][$arColumn["id"]];
                    } else
                        echo $arData["data"][$arColumn["id"]]
                    ?>
                </td>
			<?endforeach;?>
		<?endforeach;?>
		</tr>
	</tbody>
	<tfoot>

		<tr>
			<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_WEIGHT_SUM")?></b></td>
			<td align="right" colspan="<?=$colspan?>"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
		</tr>
		<tr>
			<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_SUMMARY")?></b></td>
			<td align="right" colspan="<?=$colspan?>"><span id="sale-order-full-price"><?=$arResult["ORDER_PRICE_FORMATED"]?></span></td>
		</tr>
		<?
		if (doubleval($arResult["DISCOUNT_PRICE"]) > 0)
		{
			?>
			<tr>
				<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?><?if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"])>0):?> (<?echo $arResult["DISCOUNT_PERCENT_FORMATED"];?>)<?endif;?>:</b></td>
				<td align="right" colspan="<?=$colspan?>"><span id="sale-order-full-discount-price"><?echo $arResult["DISCOUNT_PRICE_FORMATED"]?></span>
				</td>
			</tr>
			<?
		}
		/*
		if (doubleval($arResult["VAT_SUM_FORMATED"]) > 0)
		{
			?>
			<tr>
				<td align="right">
					<b><?=GetMessage("SOA_TEMPL_SUM_VAT")?></b>
				</td>
				<td align="right" colspan="<?=$colspan?>"><?=$arResult["VAT_SUM_FORMATED"]?></td>
			</tr>
			<?
		}
		*/
		if(!empty($arResult["arTaxList"]))
		{
			foreach($arResult["arTaxList"] as $val)
			{
				?>
				<tr>
					<td align="right"><?=$val["NAME"]?> <?=$val["VALUE_FORMATED"]?>:</td>
					<td align="right" colspan="<?=$colspan?>"><?=$val["VALUE_MONEY_FORMATED"]?></td>
				</tr>
				<?
			}
		}
		if (doubleval($arResult["DELIVERY_PRICE"]) > 0)
		{
			?>
			<tr>
				<td align="right">
					<b><?=GetMessage("SOA_TEMPL_SUM_DELIVERY")?></b>
				</td>
				<td align="right" colspan="<?=$colspan?>"><span id="sale-order-full-delivery-price"><?=$arResult["DELIVERY_PRICE_FORMATED"]?></span></td>
			</tr>
			<?
		}
		?>
		<tr>
			<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_IT")?></b></td>
			<td align="right" colspan="<?=$colspan?>"><b id="sale-order-full-total-price"><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></b>
			</td>
		</tr>
		<?
		if (strlen($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0)
		{
			?>
			<tr>
				<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_PAYED")?></b></td>
				<td align="right" colspan="<?=$colspan?>"><?=$arResult["PAYED_FROM_ACCOUNT_FORMATED"]?></td>
			</tr>
			<?
		}
		?>
	</tfoot>
</table>

<br /><br />
<b><?=GetMessage("SOA_TEMPL_SUM_ADIT_INFO")?></b><br /><br />

<table class="sale_order_full_table">
	<tr>
		<td width="50%" align="left" valign="top"><?=GetMessage("SOA_TEMPL_SUM_COMMENTS")?><br />
			<textarea rows="4" cols="40" name="ORDER_DESCRIPTION"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>
		</td>
	</tr>
</table>
