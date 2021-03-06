<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<a name="order_fform"></a>
<div id="order_form_div" class="order-checkout">
<NOSCRIPT>
	<div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>
<?
// echo $arParams["ALLOW_AUTO_REGISTER"];
// $arParams["ALLOW_AUTO_REGISTER"] = "Y";
if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N")
{
	echo $arParams["ALLOW_AUTO_REGISTER"];
	if(!empty($arResult["ERROR"]))
	{
		foreach($arResult["ERROR"] as $v)
			echo ShowError($v);
	}
	elseif(!empty($arResult["OK_MESSAGE"]))
	{
		foreach($arResult["OK_MESSAGE"] as $v)
			echo ShowNote($v);
	}

	include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
}
else
{
	if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y" || $arResult["NEED_REDIRECT"] == "Y")
	{
		if(strlen($arResult["REDIRECT_URL"]) > 0)
		{
			?>
			<script type="text/javascript">
			window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
			</script>
			<?
			die();
		}
		else
		{
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
		}
	}
	else
	{
		?>
		<script type="text/javascript">
		function submitForm(val)
		{
            BX('confirmorder').value = val !== 'Y' ? "N" : val;

			var orderForm = BX('ORDER_FORM');

			BX.ajax.submitComponentForm(orderForm, 'order_form_content', true);
			BX.submit(orderForm);

			return true;
		}
		function SetContact(profileId)
		{
			BX("profile_change").value = "Y";
			submitForm();
		}
		</script>
		<?if($_POST["is_ajax_post"] != "Y")
		{
			?>
        <form action="" method="POST" name="ORDER_FORM" id="ORDER_FORM" enctype="multipart/form-data">
            <?
            if ((\Bitrix\Main\Context::getCurrent()->getRequest())->get("isChangeLocation") === "Y"):?>
                <input type='hidden' name='isChangeLocation' value='Y'>
            <? endif; ?>
            <?= bitrix_sessid_post() ?>
			<div id="order_form_content">
			<?
		}
		else
		{
			$APPLICATION->RestartBuffer();
		}
		?>
		<div class="wrewfwer">
			<?if($_POST["is_ajax_post"] == "Y"):?>
				<span class="wrewfwer_ajax"></span>
			<?endif;?>
		</div>
		<?
		if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
		{
			foreach($arResult["ERROR"] as $v)
				echo ShowError($v);

			?>
			<script type="text/javascript">
				top.BX.scrollToNode(top.BX('ORDER_FORM'));
			</script>
			<?
		}

		if(count($arResult["PERSON_TYPE"]) > 1)
		{
			?>
			<b><?=GetMessage("SOA_TEMPL_PERSON_TYPE")?></b>
			<table class="sale_order_full_table">
			<tr>
			<td>
			<?
			foreach($arResult["PERSON_TYPE"] as $v)
			{
				?><input type="radio" id="PERSON_TYPE_<?= $v["ID"] ?>" name="PERSON_TYPE" value="<?= $v["ID"] ?>"<?if ($v["CHECKED"]=="Y") echo " checked=\"checked\"";?> onClick="submitForm()"> <label for="PERSON_TYPE_<?= $v["ID"] ?>"><?= $v["NAME"] ?></label><br /><?
			}
			?>
			<input type="hidden" name="PERSON_TYPE_OLD" value="<?=$arResult["USER_VALS"]["PERSON_TYPE_ID"]?>">
			</td></tr></table>
			<br /><br />
			<?
		}
		else
		{
			if(IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) > 0)
			{
				?>
				<input type="hidden" name="PERSON_TYPE" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>">
				<input type="hidden" name="PERSON_TYPE_OLD" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>">
				<?
			}
			else
			{
				foreach($arResult["PERSON_TYPE"] as $v)
				{
					?>
					<input type="hidden" id="PERSON_TYPE" name="PERSON_TYPE" value="<?=$v["ID"]?>">
					<input type="hidden" name="PERSON_TYPE_OLD" value="<?=$v["ID"]?>">
					<?
				}
			}
		}

		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props.php");
		?>
		<?
		if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d")
		{
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
		}
		else
		{
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/delivery.php");
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/paysystem.php");
		}

		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/related_props.php");
		?>
		<br /><br />
		<?
		include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/summary.php");

		if(strlen($arResult["PREPAY_ADIT_FIELDS"]) > 0)
			echo $arResult["PREPAY_ADIT_FIELDS"];
		?>
		<?if($_POST["is_ajax_post"] != "Y")
		{
			?>
				</div>
				<input type="hidden" name="confirmorder" id="confirmorder" value="Y">
				<input type="hidden" name="profile_change" id="profile_change" value="N">
				<input type="hidden" name="is_ajax_post" id="is_ajax_post" value="Y">
                <input type='hidden' name='ZIP_PROPERTY_CHANGED' value='Y'>
				<br /><br />
				<div align="right">
				<input type="button" name="submitbutton" onClick="submitForm('Y');" value="<?=GetMessage("SOA_TEMPL_BUTTON")?>">
				</div>
			</form>
			<?
			if($arParams["DELIVERY_NO_AJAX"] == "N")
			{
				$APPLICATION->AddHeadScript("/bitrix/js/main/cphttprequest.js");
				$APPLICATION->AddHeadScript("/bitrix/components/bitrix/sale.ajax.delivery.calculator/templates/.default/proceed.js");
			}
		}
		else
		{
			?>
			<script type="text/javascript">
				top.BX('confirmorder').value = 'Y';
				top.BX('profile_change').value = 'N';
// 				function include( filename ) {
// var js = document.createElement(‘script’);
// js.setAttribute(‘type’, ‘text/javascript’);
// js.setAttribute(‘src’, filename);
// js.setAttribute(‘defer’, ‘defer’);
// document.getElementsByTagName(‘HEAD’)[0].appendChild(js);
//
// // save include state for reference by include_once
// var cur_file = {};
// cur_file[window.location.href] = 1;
//
// if (!window.php_js) window.php_js = {};
// if (!window.php_js.includes) window.php_js.includes = cur_file;
// if (!window.php_js.includes[filename]) {
// window.php_js.includes[filename] = 1;
// } else {
// window.php_js.includes[filename]++;
// }
//
// return window.php_js.includes[filename];
// }
//
// BX.addCustomEvent(‘onAjaxSuccess’, afterFormReload);
// function afterFormReload(){
// include(‘http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js’);
// }
			</script>
			<?
			die();
		}
	}
}
?>
</div>
