<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="bx-auth-reg">

    <? if ($USER->IsAuthorized()): ?>

        <p class="reg-text-complete"><? echo GetMessage("MAIN_REGISTER_AUTH") ?></p>

    <? else: ?>
        <?
        if (count($arResult["ERRORS"]) > 0):
            foreach ($arResult["ERRORS"] as $key => $error)
                if (intval($key) == 0 && $key !== 0)
                    $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;" . GetMessage("REGISTER_FIELD_" . $key) . "&quot;", $error);

            ShowError(implode("<br />", $arResult["ERRORS"]));

        elseif ($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
            ?>
            <p class="text-message"><? echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT") ?></p>
        <? endif ?>

        <form method="post" action="<?= POST_FORM_ACTION_URI ?>" name="regform" enctype="multipart/form-data">
            <?
            if ($arResult["BACKURL"] <> ''):
                ?>
                <input class="sci-login__input" type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
            <?
            endif;
            ?>

                <div class="reg-title">
                   <?= GetMessage("AUTH_REGISTER") ?>
                </div>
                <? foreach ($arResult["SHOW_FIELDS"] as $FIELD): ?>
                    <? if ($FIELD == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true): ?>
                        <div class="input_group">
                            <div class="input-reg"><? echo GetMessage("main_profile_time_zones_auto") ?><? if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"): ?>
                                    <span class="starrequired">*</span><? endif ?></div>
                            <div class="input-reg">
                                <select class="sci-login__input" name="REGISTER[AUTO_TIME_ZONE]"
                                        onchange="this.form.elements['REGISTER[TIME_ZONE]'].disabled=(this.value != 'N')">
                                    <option value=""><? echo GetMessage("main_profile_time_zones_auto_def") ?></option>
                                    <option value="Y"<?= $arResult["VALUES"][$FIELD] == "Y" ? " selected=\"selected\"" : "" ?>><? echo GetMessage("main_profile_time_zones_auto_yes") ?></option>
                                    <option value="N"<?= $arResult["VALUES"][$FIELD] == "N" ? " selected=\"selected\"" : "" ?>><? echo GetMessage("main_profile_time_zones_auto_no") ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="input_group">
                            <div class="input-reg"><? echo GetMessage("main_profile_time_zones_zones") ?></div>
                            <div class="input-reg">
                                <select class="sci-login__input" name="REGISTER[TIME_ZONE]"<? if (!isset($_REQUEST["REGISTER"]["TIME_ZONE"])) echo 'disabled="disabled"' ?>>
                                    <? foreach ($arResult["TIME_ZONE_LIST"] as $tz => $tz_name): ?>
                                        <option value="<?= htmlspecialcharsbx($tz) ?>"<?= $arResult["VALUES"]["TIME_ZONE"] == $tz ? " selected=\"selected\"" : "" ?>><?= htmlspecialcharsbx($tz_name) ?></option>
                                    <? endforeach ?>
                                </select>
                            </div>
                        </div>
                    <? else: ?>
                        <div class="input_group">
                            <div class="input-reg"><?= GetMessage("REGISTER_FIELD_" . $FIELD) ?>
                                :<? if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"): ?><span
                                        class="starrequired">*</span><? endif ?></div>
                            <div class="input-reg"><?
                                switch ($FIELD) {
                                    case "PASSWORD":
                                        ?><input size="30" type="password" name="REGISTER[<?= $FIELD ?>]"
                                                 value="<?= $arResult["VALUES"][$FIELD] ?>" autocomplete="off"
                                                 class="sci-login__input"/>
                                    <?
                                    if ($arResult["SECURE_AUTH"]): ?>
                                        <span class="bx-auth-secure" id="bx_auth_secure" title="<?
                                        echo GetMessage("AUTH_SECURE_NOTE") ?>" style="display:none">
					<div class="bx-auth-secure-icon"></div>
				</span>
                                        <noscript>
				<span class="bx-auth-secure" title="<?
                echo GetMessage("AUTH_NONSECURE_NOTE") ?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
                                        </noscript>
                                        <script type="text/javascript">
                                            document.getElementById('bx_auth_secure').style.display = 'inline-block';
                                        </script>
                                    <?
                                    endif ?>
                                        <?
                                        break;
                                    case "CONFIRM_PASSWORD":
                                        ?><input class="sci-login__input" size="30" type="password"
                                                 name="REGISTER[<?= $FIELD ?>]"
                                                 value="<?= $arResult["VALUES"][$FIELD] ?>" autocomplete="off" /><?
                                        break;

                                    case "PERSONAL_GENDER":
                                        ?><select class="sci-login__input" name="REGISTER[<?= $FIELD ?>]">
                                        <option value=""><?= GetMessage("USER_DONT_KNOW") ?></option>
                                        <option value="M"<?= $arResult["VALUES"][$FIELD] == "M" ? " selected=\"selected\"" : "" ?>><?= GetMessage("USER_MALE") ?></option>
                                        <option value="F"<?= $arResult["VALUES"][$FIELD] == "F" ? " selected=\"selected\"" : "" ?>><?= GetMessage("USER_FEMALE") ?></option>
                                        </select><?
                                        break;

                                    case "PERSONAL_COUNTRY":
                                    case "WORK_COUNTRY":
                                        ?><select class="sci-login__input" name="REGISTER[<?= $FIELD ?>]"><?
                                        foreach ($arResult["COUNTRIES"]["reference_id"] as $key => $value) {
                                            ?>
                                            <option value="<?= $value ?>"<?
                                            if ($value == $arResult["VALUES"][$FIELD]):?> selected="selected"<?endif ?>><?= $arResult["COUNTRIES"]["reference"][$key] ?></option>
                                            <?
                                        }
                                        ?></select><?
                                        break;

                                    case "PERSONAL_PHOTO":
                                    case "WORK_LOGO":
                                        ?><input class="sci-login__input" size="30" type="file"
                                                 name="REGISTER_FILES_<?= $FIELD ?>" /><?
                                        break;

                                    case "PERSONAL_NOTES":
                                    case "WORK_NOTES":
                                        ?><textarea cols="30" rows="5"
                                                    name="REGISTER[<?= $FIELD ?>]"><?= $arResult["VALUES"][$FIELD] ?></textarea><?
                                        break;
                                    default:
                                        if ($FIELD == "PERSONAL_BIRTHDAY"):?>
                                            <small><?= $arResult["DATE_FORMAT"] ?></small><br/><?endif;
                                        ?><input class="sci-login__input" size="30" type="text"
                                                 name="REGISTER[<?= $FIELD ?>]"
                                                 value="<?= $arResult["VALUES"][$FIELD] ?>" /><?
                                        if ($FIELD == "PERSONAL_BIRTHDAY")
                                            $APPLICATION->IncludeComponent(
                                                'bitrix:main.calendar',
                                                '',
                                                array(
                                                    'SHOW_INPUT' => 'N',
                                                    'FORM_NAME' => 'regform',
                                                    'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
                                                    'SHOW_TIME' => 'N'
                                                ),
                                                null,
                                                array("HIDE_ICONS" => "Y")
                                            );
                                        ?><?
                                } ?></div>
                        </div>
                    <? endif ?>
                <? endforeach ?>
                <? // ********************* User properties ***************************************************?>
                <? if ($arResult["USER_PROPERTIES"]["SHOW"] == "Y"): ?>
                    <div>
                        <div colspan="2"><?= strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB") ?></div>
                    </div>
                    <? foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField): ?>
                        <div>
                            <div><?= $arUserField["EDIT_FORM_LABEL"] ?>:<? if ($arUserField["MANDATORY"] == "Y"): ?>
                                    <span class="starrequired">*</span><? endif; ?></div>
                            <div>
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:system.field.edit",
                                    $arUserField["USER_TYPE"]["USER_TYPE_ID"],
                                    array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "regform"), null, array("HIDE_ICONS" => "Y")); ?></div>
                        </div>
                    <? endforeach; ?>
                <? endif; ?>
                <? // ******************** /User properties ***************************************************?>
                <?
                /* CAPTCHA */
                if ($arResult["USE_CAPTCHA"] == "Y") {
                    ?>
                    <div>
                        <div colspan="2"><b><?= GetMessage("REGISTER_CAPTCHA_TITLE") ?></b></div>
                    </div>
                    <div>
                        <div></div>
                        <div>
                            <input class="sci-login__input" type="hidden" name="captcha_sid"
                                   value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>"
                                 width="180" height="40" alt="CAPTCHA"/>
                        </div>
                    </div>
                    <div>
                        <div><?= GetMessage("REGISTER_CAPTCHA_PROMT") ?>:<span class="starrequired">*</span></div>
                        <div><input class="sci-login__input" type="text" name="captcha_word" maxlength="50" value=""/>
                        </div>
                    </div>
                    <?
                }
                /* !CAPTCHA */
                ?>
                <div>
                    <div></div>
                    <div>
                        <input class="sci-login__button sci-login__button_green" type="submit" name="register_submit_button"
                                value="<?= GetMessage("AUTH_REGISTER") ?>"/></div>
                </div>
                <p class="subtext"><? echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?></p>
                <p class="subtext"><span class="starrequired">*</span><?= GetMessage("AUTH_REQ") ?></p>

        </form>
    <? endif ?>
</div>
