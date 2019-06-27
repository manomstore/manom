<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>


            <section id="pb-info" class="pb-info personal-block__section">
                <?ShowError($arResult["strProfileError"]);?>
                <?
                if ($arResult['DATA_SAVED'] == 'Y')
                    ShowNote(GetMessage('PROFILE_DATA_SAVED'));
                ?>
                <script type="text/javascript">
                    <!--
                    var opened_sections = [<?
                        $arResult["opened"] = $_COOKIE[$arResult["COOKIE_PREFIX"]."_user_profile_open"];
                        $arResult["opened"] = preg_replace("/[^a-z0-9_,]/i", "", $arResult["opened"]);
                        if (strlen($arResult["opened"]) > 0)
                        {
                            echo "'".implode("', '", explode(",", $arResult["opened"]))."'";
                        }
                        else
                        {
                            $arResult["opened"] = "reg";
                            echo "'reg'";
                        }
                        ?>];
                    //-->

                    var cookie_prefix = '<?=$arResult["COOKIE_PREFIX"]?>';
                </script>
                <form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>" enctype="multipart/form-data">
                    <?=$arResult["BX_SESSION_CHECK"]?>
                    <input type="hidden" name="lang" value="<?=LANG?>" />
                    <input type="hidden" name="ID" value=<?=$arResult["ID"]?> />

<!--                    <div class="profile-link profile-user-div-link"><a title="--><?//=GetMessage("REG_SHOW_HIDE")?><!--" href="javascript:void(0)" onclick="SectionClick('reg')">--><?//=GetMessage("REG_SHOW_HIDE")?><!--</a></div>-->
                    <div class="profile-block-<?=strpos($arResult["opened"], "reg") === false ? "hidden" : "shown"?>" id="user_div_reg">

                    <h2 class="pb-info__title">Мои настройки</h2>
                    <div class="pb-info__block">
                        <div class="pb-info__col1">
                            <div id="pb-info__col1-input">
                                <label class="pb-info__col-text"><?=GetMessage('NAME')?> <input name="NAME" type="text" class="pb-info__col-input" value="<?=$arResult["arUser"]["NAME"]?>"></label>
                                <label class="pb-info__col-text"><?=GetMessage('LAST_NAME')?> <input name="LAST_NAME" type="text" class="pb-info__col-input" value="<?=$arResult["arUser"]["LAST_NAME"]?>"></label>
                                <label class="pb-info__col-text"><?=GetMessage('SECOND_NAME')?> <input name="SECOND_NAME" type="text" class="pb-info__col-input" value="<?=$arResult["arUser"]["SECOND_NAME"]?>"></label>
                                <label class="pb-info__col-text"><?=GetMessage('USER_PHONE')?> <input name="PERSONAL_PHONE" type="text" class="pb-info__col-input" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>"></label>
                                <label class="pb-info__col-text"><?=GetMessage('EMAIL')?><?if($arResult["EMAIL_REQUIRED"]):?><span class="starrequired">*</span><?endif?> <input type="text" name="EMAIL" class="pb-info__col-input" value="<? echo $arResult["arUser"]["EMAIL"]?>"></label>
                                <label class="pb-info__col-text"><?=GetMessage('USER_CITY')?> <input type="text" name="PERSONAL_CITY" class="pb-info__col-input" value="<?=$arResult["arUser"]["PERSONAL_CITY"]?>"></label>
                                <label class="pb-info__col-text"><?=GetMessage('USER_STATE')?> <input type="text" name="PERSONAL_STATE" class="pb-info__col-input" value="<?=$arResult["arUser"]["PERSONAL_STATE"]?>"></label>
                                <label class="pb-info__col-text"><?=GetMessage("USER_STREET")?> <textarea name="PERSONAL_STREET" class="pb-info__col-input"><?=$arResult["arUser"]["PERSONAL_STREET"]?></textarea></label>
                                <label class="pb-info__col-text"><?=GetMessage('USER_ZIP')?> <input type="text" name="PERSONAL_ZIP" class="pb-info__col-input" value="<?=$arResult["arUser"]["PERSONAL_ZIP"]?>"></label>
                                <?if($arResult["arUser"]["EXTERNAL_AUTH_ID"] == ''):?>
                                    <label class="pb-info__col-text"><?=GetMessage('NEW_PASSWORD_REQ')?> <input type="password" name="NEW_PASSWORD" class="pb-info__col-input" value=""></label>
                                    <?if($arResult["SECURE_AUTH"]):?>
                                        <span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
                                            <div class="bx-auth-secure-icon"></div>
                                        </span>
                                        <noscript>
                                            <span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
                                                <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                                            </span>
                                        </noscript>
                                        <script type="text/javascript">
                                            document.getElementById('bx_auth_secure').style.display = 'inline-block';
                                        </script>
                                    <?endif?>
                                    <label class="pb-info__col-text"><?=GetMessage('NEW_PASSWORD_CONFIRM')?> <input type="password" name="NEW_PASSWORD_CONFIRM" autocomplete="off" class="pb-info__col-input" value=""></label>
                                <?endif?>

                            </div>
                            <div id="pb-info__col1-text" class="pb-info__col1">
                                <div class="pb-info__col-text">ФИО: <br /><span><?=$arResult["arUser"]["LAST_NAME"]?> <?=$arResult["arUser"]["NAME"]?> <?=$arResult["arUser"]["SECOND_NAME"]?></span></div>
                                <div class="pb-info__col-text"><?=GetMessage('USER_PHONE')?><br /> <span><?=$arResult["arUser"]["PERSONAL_PHONE"]?></span></div>
                                <div class="pb-info__col-text"><?=GetMessage('EMAIL')?><?if($arResult["EMAIL_REQUIRED"]):?><span class="starrequired">*</span><?endif?><br /> <span><? echo $arResult["arUser"]["EMAIL"]?></span></p>
                                <div class="pb-info__col-text">Адрес доставки:<br /> <span><?=$arResult["arUser"]["PERSONAL_CITY"]?>, <?=$arResult["arUser"]["PERSONAL_STATE"]?>, <?=$arResult["arUser"]["PERSONAL_STREET"]?> <?=$arResult["arUser"]["PERSONAL_ZIP"]?></span></div>
                                <div class="pb-info__col-text">Пароль:<br /> <span>********</span></div>
                            </div>
                        </div>
                        <div class="pb-info__col2">
<!--                            <div id="pb-info__col2-input">-->
<!--                                <label class="pb-info__col-text"><input type="text" class="pb-info__col-input" value="facebook"></label>-->
<!--                                <label class="pb-info__col-text"><input type="text" class="pb-info__col-input" value="instagram"></label>-->
<!--                                <label class="pb-info__col-text"><input type="text" class="pb-info__col-input" value="twitter"></label>-->
<!--                                <label class="pb-info__col-text"><input type="text" class="pb-info__col-input" value="vkontakte"></label>-->
<!--                            </div>-->
                            <div id="pb-info__col2-text">
                                <p class="pb-info__col-title">Привязать социальные сети</p>
                                <div class="bx-auth-profile">

                                    <?
                                    if($arResult["SOCSERV_ENABLED"])
                                    {
                                        $APPLICATION->IncludeComponent("bitrix:socserv.auth.split", "", array(
                                            "SHOW_PROFILES" => "Y",
                                            "ALLOW_DELETE" => "Y"
                                        ),
                                            false
                                        );
                                    }
                                    ?>
                                </div>
<!--                                <a href="https://www.facebook.com/" class="pb-info__col-link" target="_blank">facebook</a>-->
<!--                                <a href="https://www.instagram.com/" class="pb-info__col-link" target="_blank">instagram</a>-->
<!--                                <a href="https://twitter.com/" class="pb-info__col-link" target="_blank">twitter</a>-->
<!--                                <a href="https://vk.com/" class="pb-info__col-link" target="_blank">vkontakte</a>-->
                            </div>
                        </div>
                        <div class="pb-info__col3">
                            <?
														$photoSRC = false;
														$rsUsers = CUser::GetList(($by="personal_country"), ($order="desc"), array('ID' => $arResult["arUser"]['ID']), array('FIELDS' => array('PERSONAL_PHOTO')));
														if($resUser = $rsUsers->Fetch()) {
															$photoSRC = CFile::GetPath($resUser['PERSONAL_PHOTO']);
														}
                            if ($photoSRC)
                            {
                                ?>
                                <br />
                                <div class="profile-info-image" style="background: url('<?=$photoSRC?>') center no-repeat;"></div>
                                <?
                            }?>
                            <label id="pb-info__col3-input" class="pb-info__col-text3 custom-save">
<!--                                <input type="file" class="pb-info__col-input3" value="">-->
                                <?=$arResult["arUser"]["PERSONAL_PHOTO_INPUT"]?>
                            </label>
                        </div>
                            <div class="pb-info-input-group">
                                <input id="pb-info__button" type="checkbox" name="pb-info__button" value="">
                                <label for="pb-info__button" class="pb-info__button">Редактировать</label>
                                <input style="display: none;" id="pb-info__button_save" type="submit" name="save" class="pb-info__button" value="Сохранить">
                            </div>
                    </div>
                </form>
            </section>
