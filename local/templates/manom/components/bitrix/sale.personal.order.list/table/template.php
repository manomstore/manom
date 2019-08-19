<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?
?>
<table class="personal" border="0" cellspacing="0" cellpadding="5">
	<tr>
        <td width="25%" rowspan="3" valign="top" class="history-left-menu">
            <aside class="personal__aside">
                <h1 class="personal__title">Личный кабинет</h1>
                <p onclick="location.href = '/user/profile.php'" class="personal-nav__item personal-nav__name" data-id="pb-info">Мои настройки</p>
                <p class="personal-nav__name" data-id="pb-info">Настройки:</p>
                <?
                if ($_REQUEST["filter_history"] == "Y")
                {
                    ?><a class="personal-nav__item" href="<?=$arResult["CURRENT_PAGE"]?>?filter_history=N"><?echo GetMessage("STPOL_CUR_ORDERS")?></a><?
                }
                else
                {
                    ?><a class="personal-nav__item" href="<?=$arResult["CURRENT_PAGE"]?>?filter_history=Y&filter_status=F"><?echo GetMessage("STPOL_ORDERS_HISTORY")?></a><?
                }
                ?>
                <p class="personal-nav__name">Покупки:</p>
                <a href="/user/history.php" id="personal-nav__item2" class="personal-nav__item">История покупок</a>
                <a href="/user/favorite/" id="personal-nav__item4" class="personal-nav__item">Товары в избранном</a>
                <a href="/catalog/compare/" id="personal-nav__item4" class="personal-nav__item">Сравнение товаров</a>
                <p class="personal-nav__name">Моя активность:</p>
<!--                <a href="/user/review/add-list/" id="personal-nav__item4" class="personal-nav__item">Добавить отзыв товару</a>-->
<!--                <a href="/user/review/" id="personal-nav__item4" class="personal-nav__item">Мои отзывы</a>-->
            </aside>
            <div class="attention">
                <?echo GetMessage("STPOL_HINT")?><br /><br />
                <?echo GetMessage("STPOL_HINT1")?>
            </div>
        </td>
        <td width="5%" class="helper-history-order" rowspan="3">&nbsp;</td>
		<td width="70%">
			<?
			$bNoOrder = true;
			foreach($arResult["ORDER_BY_STATUS"] as $key => $val)
			{
				$bShowStatus = true;
				foreach($val as $vval)
				{
					$bNoOrder = false;
					if($bShowStatus)
					{
						?><h2><?echo GetMessage("STPOL_STATUS")?> "<?=$arResult["INFO"]["STATUS"][$key]["NAME"] ?>"</h2>
						<small><?=$arResult["INFO"]["STATUS"][$key]["DESCRIPTION"] ?></small>
					<?
					}
					$bShowStatus = false;
					?>
					<table class="sale_personal_order_list">
						<tr>
							<td class="padding-table-top">
								<b>
								<?echo GetMessage("STPOL_ORDER_NO")?>
								<a class="link-sale_personal_order_list" title="<?echo GetMessage("STPOL_DETAIL_ALT")?>" href="<?=$vval["ORDER"]["URL_TO_DETAIL"] ?>"><?=$vval["ORDER"]["ACCOUNT_NUMBER"]?></a>
								<?echo GetMessage("STPOL_FROM")?>
								<?= $vval["ORDER"]["DATE_INSERT"]; ?>
								</b>
								<?
								if ($vval["ORDER"]["CANCELED"] == "Y")
									echo GetMessage("STPOL_CANCELED");
								?>
								<br />
								<b>
								<?echo GetMessage("STPOL_SUM")?>
								<?=$vval["ORDER"]["FORMATED_PRICE"]?>
								</b>
								<?if($vval["ORDER"]["PAYED"]=="Y")
									echo GetMessage("STPOL_PAYED_Y");
								else
									echo GetMessage("STPOL_PAYED_N");
								?>
								<?if(IntVal($vval["ORDER"]["PAY_SYSTEM_ID"])>0)
									echo GetMessage("P_PAY_SYS").$arResult["INFO"]["PAY_SYSTEM"][$vval["ORDER"]["PAY_SYSTEM_ID"]]["NAME"]?>
								<br />
								<b><?echo GetMessage("STPOL_STATUS_T")?></b>
								<?=$arResult["INFO"]["STATUS"][$vval["ORDER"]["STATUS_ID"]]["NAME"]?>
								<?echo GetMessage("STPOL_STATUS_FROM")?>
								<?=$vval["ORDER"]["DATE_STATUS"];?>
								<br />
								<?if(IntVal($vval["ORDER"]["DELIVERY_ID"])>0)
								{
									echo "<b>".GetMessage("P_DELIVERY")."</b>".$arResult["INFO"]["DELIVERY"][$vval["ORDER"]["DELIVERY_ID"]]["NAME"];
								}
								elseif (strpos($vval["ORDER"]["DELIVERY_ID"], ":") !== false)
								{
									echo "<b>".GetMessage("P_DELIVERY")."</b>";
									$arId = explode(":", $vval["ORDER"]["DELIVERY_ID"]);
									echo $arResult["INFO"]["DELIVERY_HANDLERS"][$arId[0]]["NAME"]." (".$arResult["INFO"]["DELIVERY_HANDLERS"][$arId[0]]["PROFILES"][$arId[1]]["TITLE"].")";
								}
								?>
							</td>
						</tr>
						<tr>
							<td>
								<table class="sale_personal_order_list_table">
									<tr>
										<td width="0%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
										<td width="100%">
											<b><?echo GetMessage("STPOL_CONTENT")?></b>
										</td>
										<td width="0%">&nbsp;</td>
									</tr>
									<?
									foreach ($vval["BASKET_ITEMS"] as $vvval)
									{
										$measure = (isset($vvval["MEASURE_TEXT"])) ? $vvval["MEASURE_TEXT"] :GetMessage("STPOL_SHT");
										?>
										<tr>
											<td width="0%">&nbsp;&nbsp;&nbsp;&nbsp;</td>
											<td width="100%">

												<?
												if (strlen($vvval["DETAIL_PAGE_URL"]) > 0)
													echo "<a class=\"link-sale_personal_order_list-alt\" href=\"".$vvval["DETAIL_PAGE_URL"]."\">";
												echo $vvval["NAME"];
												if (strlen($vvval["DETAIL_PAGE_URL"]) > 0)
													echo "</a>";
												?>
											</td>
											<td width="0%" nowrap><?= $vvval["QUANTITY"] ?> <?=$measure?></td>
										</tr>
										<?
									}
									?>
								</table>
							</td>
						</tr>
						<tr>
							<td align="right" class="padding-table-bottom">
								<a class="link-sale_personal_order_list" title="<?= GetMessage("STPOL_DETAIL_ALT") ?>" href="<?=$vval["ORDER"]["URL_TO_DETAIL"]?>"><?= GetMessage("STPOL_DETAILS") ?></a>
<!--								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
<!--								<a class="link-sale_personal_order_list" title="--><?//= GetMessage("STPOL_REORDER") ?><!--" href="--><?//=$vval["ORDER"]["URL_TO_COPY"]?><!--">--><?//= GetMessage("STPOL_REORDER1") ?><!--</a>-->
								<?if ($vval["ORDER"]["CAN_CANCEL"] == "Y"):?>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a class="link-sale_personal_order_list" title="<?= GetMessage("STPOL_CANCEL") ?>" href="<?=$vval["ORDER"]["URL_TO_CANCEL"]?>"><?= GetMessage("STPOL_CANCEL") ?></a>
								<?endif;?>
							</td>
						</tr>
					</table>
					<br />
				<?
				}
				?>
				<br />
				<?
			}

			if ($bNoOrder)
			{
				?><center><?echo GetMessage("STPOL_NO_ORDERS")?></center><?
			}
			?>
		</td>
	</tr>
</table>