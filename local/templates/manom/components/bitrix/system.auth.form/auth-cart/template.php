<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
CJSCore::Init();
global $USER;
global $APPLICATION;
$dir = $APPLICATION->GetCurDir();
?>

<?if ($USER->IsAuthorized() and $dir != '/user/') {
?>
    <div class="sci-login">
        <h3 class="sci-login__title">Приветствуем Вас! <?=$arResult['USER_NAME']?></h3>
        <p class="sci-login__text">Вы можете изменить информацию в <a href="<?=$arResult["PROFILE_URL"]?>" title="<?=GetMessage("AUTH_PROFILE")?>">личном кабинете</a></p>
        <form class="sci-login__form" action="<?=$arResult["AUTH_URL"]?>">
            <?foreach ($arResult["GET"] as $key => $value):?>
                <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
            <?endforeach?>
            <input type="hidden" name="logout" value="yes" />
            <input class="sci-login__button" type="submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" />
        </form>
    </div>
<?}else{?>
    <? if ($arResult["FORM_TYPE"] == "login"){?>
        <div class="sci-login">
            <h3 class="sci-login__title">Войти в существующий аккаунт</h3>
            <p class="sci-login__text">Войдите в существующий аккаунт для быстрого оформления покупки.</p>
            <form class="sci-login__form" name="system_auth_form<?=$arResult["RND"] ?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"] ?>">
                <?if ($arResult["BACKURL"] <> ''){ ?>
                    <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                <?}?>
                <?foreach ($arResult["POST"] as $key => $value){ ?>
                    <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
                <?}?>
                <input type="hidden" name="AUTH_FORM" value="Y"/>
                <input type="hidden" name="TYPE" value="AUTH"/>

                <div class="errortext"><?if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])ShowMessage($arResult['ERROR_MESSAGE']); ?></div>
                <div class="email-input">
                    <label class="sci-login__label" for="sci-login__email">E-mail</label>
                    <input type="email" name="USER_LOGIN" id="sci-login__email" class="sci-login__input" maxlength="50" value="" placeholder="Ваш e-mail" required>
                </div>
                <script>
                    BX.ready(function () {
                        var loginCookie = BX.getCookie("<?=CUtil::JSEscape($arResult["~LOGIN_COOKIE_NAME"])?>");
                        if (loginCookie) {
                            var form = document.forms["system_auth_form<?=$arResult["RND"]?>"];
                            var loginInput = form.elements["USER_LOGIN"];
                            loginInput.value = loginCookie;
                        }
                    });
                </script>

                <div class="password-input">
                    <label class="sci-login__label" for="sci-login__password">Пароль</label>
                    <input type="password" name="USER_PASSWORD" id="sci-login__password" class="sci-login__input" autocomplete="off" placeholder="Ваш пароль" required>
                </div>
                <div class="sci-login__social">
                    <span>Войти через соц.сети: </span>
                    <a href="#" class="sci-login__social-link">
                        <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/s-instagram.png" alt="">
                    </a>
                    <a href="#" class="sci-login__social-link">
                        <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/s-telegram.png" alt="">
                    </a>
                    <a href="#" class="sci-login__social-link">
                        <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/s-facebook.png" alt="">
                    </a>
                    <a href="#" class="sci-login__social-link">
                        <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/s-vk.png" alt="">
                    </a>
                </div>
                <div>
                    <?if ($arResult["SECURE_AUTH"]){?>
                        <span class="bx-auth-secure" id="bx_auth_secure<?= $arResult["RND"] ?>" title="<?echo GetMessage("AUTH_SECURE_NOTE") ?>" style="display:none">
					            <div class="bx-auth-secure-icon"></div>
				            </span>
                        <noscript>
				            <span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE") ?>">
					            <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				            </span>
                        </noscript>
                        <script type="text/javascript">
                            document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
                        </script>
                    <?}?>
                    <?if ($arResult["STORE_PASSWORD"] == "Y"){?>
                        <tr>
                            <td valign="top"><input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y"/>
                            </td>
                            <td width="100%"><label for="USER_REMEMBER_frm" title="<?= GetMessage("AUTH_REMEMBER_ME") ?>"><?
                                    echo GetMessage("AUTH_REMEMBER_SHORT") ?></label></td>
                        </tr>
                    <?}?>
                    <?if ($arResult["CAPTCHA_CODE"]){?>
                        <tr>
                            <td colspan="2">
                                <?
                                echo GetMessage("AUTH_CAPTCHA_PROMT") ?>:<br/>
                                <input type="hidden" name="captcha_sid" value="<?
                                echo $arResult["CAPTCHA_CODE"] ?>"/>
                                <img src="/bitrix/tools/captcha.php?captcha_sid=<?
                                echo $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA"/><br/><br/>
                                <input type="text" name="captcha_word" maxlength="50" value=""/></td>
                        </tr>
                    <?}?>
                    <div>
                        <input class="sci-login__button" type="submit" name="Login" value="<?= GetMessage("AUTH_LOGIN_BUTTON") ?>"/>
                        <a href="<?= $arResult["AUTH_REGISTER_URL"] ?>"
                           rel="nofollow" class="sci-login__button sci-login__button_green">Регистрация</a>
                    </div>

<!--                    --><?//if ($arResult["NEW_USER_REGISTRATION"] == "Y"){?>
<!--                        <tr>-->
<!--                            <td colspan="2">-->
<!--                                <noindex><a href="--><?//= $arResult["AUTH_REGISTER_URL"] ?><!--"-->
<!--                                            rel="nofollow">--><?//= GetMessage("AUTH_REGISTER") ?><!--</a></noindex>-->
<!--                                <br/></td>-->
<!--                        </tr>-->
<!--                    --><?//}?>

                    <tr>
                        <td colspan="2">
                            <noindex><a href="<?= $arResult["AUTH_FORGOT_PASSWORD_URL"] ?>"
                                        rel="nofollow"><?= GetMessage("AUTH_FORGOT_PASSWORD_2") ?></a></noindex>
                        </td>
                    </tr>
                    <?if ($arResult["AUTH_SERVICES"]){?>
                        <tr>
                            <td colspan="2">
                                <div class="bx-auth-lbl"><?= GetMessage("socserv_as_user_form") ?></div>
                                <?
                                $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons",
                                    array(
                                        "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
                                        "SUFFIX" => "form",
                                    ),
                                    $component,
                                    array("HIDE_ICONS" => "Y")
                                );
                                ?>
                            </td>
                        </tr>
                    <?}?>
                </div>
            </form>
        </div>

        <?if ($arResult["AUTH_SERVICES"]){?>
            <?
            $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
                array(
                    "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
                    "AUTH_URL" => $arResult["AUTH_URL"],
                    "POST" => $arResult["POST"],
                    "POPUP" => "Y",
                    "SUFFIX" => "form",
                ),
                $component,
                array("HIDE_ICONS" => "Y")
            );
            ?>
        <?}?>
    <?}elseif ($arResult["FORM_TYPE"] == "otp") {?>
        <form name="system_auth_form<?= $arResult["RND"] ?>" method="post" target="_top"
              action="<?= $arResult["AUTH_URL"] ?>">
            <?if ($arResult["BACKURL"] <> ''){?>
                <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
            <?}?>
            <input type="hidden" name="AUTH_FORM" value="Y"/>
            <input type="hidden" name="TYPE" value="OTP"/>
            <table width="95%">
                <tr>
                    <td colspan="2">
                        <?
                        echo GetMessage("auth_form_comp_otp") ?><br/>
                        <input type="text" name="USER_OTP" maxlength="50" value="" size="17" autocomplete="off"/></td>
                </tr>
                <?
                if ($arResult["CAPTCHA_CODE"]){?>
                    <tr>
                        <td colspan="2">
                            <?
                            echo GetMessage("AUTH_CAPTCHA_PROMT") ?>:<br/>
                            <input type="hidden" name="captcha_sid" value="<?
                            echo $arResult["CAPTCHA_CODE"] ?>"/>
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?
                            echo $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA"/><br/><br/>
                            <input type="text" name="captcha_word" maxlength="50" value=""/></td>
                    </tr>
                <?}?>
                <?
                if ($arResult["REMEMBER_OTP"] == "Y"){?>
                    <tr>
                        <td valign="top"><input type="checkbox" id="OTP_REMEMBER_frm" name="OTP_REMEMBER" value="Y"/>
                        </td>
                        <td width="100%"><label for="OTP_REMEMBER_frm" title="<?
                            echo GetMessage("auth_form_comp_otp_remember_title") ?>"><?
                                echo GetMessage("auth_form_comp_otp_remember") ?></label></td>
                    </tr>
                <?}?>
                <tr>
                    <td colspan="2"><input type="submit" name="Login" value="<?= GetMessage("AUTH_LOGIN_BUTTON") ?>"/>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <noindex><a href="<?= $arResult["AUTH_LOGIN_URL"] ?>" rel="nofollow"><?
                                echo GetMessage("auth_form_comp_auth") ?></a></noindex>
                        <br/></td>
                </tr>
            </table>
        </form>
    <?}?>
<?}?>
