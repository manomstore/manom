<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

ShowMessage($arParams["~AUTH_RESULT"]);

?>
<div class="forget-passwd">
<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
	<p>
	<?=GetMessage("AUTH_FORGOT_PASSWORD_1")?>
	</p>
    <div class="auth-get"><?=GetMessage("AUTH_GET_CHECK_STRING")?></div>
    <div class="form-group">
        <label class="sci-login__label" for="sci-login__password"><?=GetMessage("AUTH_EMAIL")?></label>
        <input id="sci-login__password" class="sci-login__input" type="text" name="USER_EMAIL" maxlength="255" />
    </div>
    <input class="sci-login__button" type="submit" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" />
    <? ShowMessage($arResult['ERROR_MESSAGE']) ?>
    <p>
    <a class="auth-link" href="/auth/"><?=GetMessage("AUTH_AUTH")?></a>
    </p>
</form>
<script type="text/javascript">
document.bform.USER_LOGIN.focus();
</script>
</div>