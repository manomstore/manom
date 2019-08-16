<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
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
?>
<? if (!$USER->IsAuthorized()) {
	?>
	<? if ($arResult["FORM_TYPE"] == "login") { ?>
    <div id="popap-login" class="popup-login">
      <div class="popup-login__wrapper">
        <input class="popup-login__radio visually-hidden"
               type="radio"
               name="login-toggle"
               id="sing-in"
               checked>
        <input class="popup-login__radio visually-hidden"
               type="radio"
               name="login-toggle"
               id="sing-up">

        <ul class="popup-login__toggles">
          <li>
            <label class="popup-login__toggle" for="sing-in">
              Войти в аккаунт
            </label>
          </li>
          <li>
            <label class="popup-login__toggle" for="sing-up">
              Регистрация
            </label>
          </li>
        </ul>

        <div class="popup-login__block popup-login__block--sing-in">
          <form class="popup-login__form"
                name="system_auth_form<?= $arResult["RND"] ?>"
                method="post"
                target="_top"
                action="<?= $arResult["AUTH_URL"] ?>"
          >
						<? if ($arResult["BACKURL"] <> '') { ?>
              <input type="hidden"
                     name="backurl"
                     value="<?= $arResult["BACKURL"] ?>"
              />
						<? } ?>
						<? foreach ($arResult["POST"] as $key => $value) { ?>
              <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
						<? } ?>
            <input type="hidden" name="AUTH_FORM" value="Y"/>
            <input type="hidden" name="TYPE" value="AUTH"/>

            <div class="popup-login__field">
              <label class="popup-login__label" for="sci-login__email">E-mail</label>
              <input class="popup-login__input"
                     type="email"
                     name="USER_LOGIN"
                     id="sci-login__email"
                     maxlength="50"
                     value=""
                     placeholder="Введите e-mail"
                     required
              >
            </div>
            <script>
              BX.ready(function() {
                var loginCookie = BX.getCookie("<?=CUtil::JSEscape($arResult["~LOGIN_COOKIE_NAME"])?>");
                if (loginCookie) {
                  var form = document.forms["system_auth_form<?=$arResult["RND"]?>"];
                  var loginInput = form.elements["USER_LOGIN"];
                  loginInput.value = loginCookie;
                }
              });
            </script>
            <div class="popup-login__field">
              <label class="popup-login__label" for="sci-login__password">Пароль</label>
              <input class="popup-login__input"
                     type="password"
                     name="USER_PASSWORD"
                     id="sci-login__password"
                     autocomplete="off"
                     placeholder="Ваш пароль"
                     required
              >
              <div class="errortext">
								<? if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR']) {
									ShowMessage($arResult['ERROR_MESSAGE']);
								} ?>
              </div>
            </div>
						<? if ($arResult["STORE_PASSWORD"] == "Y") { ?>
              <label class="popup-login__label-check popup-login__label-check--remember"
                     title="<?= GetMessage("AUTH_REMEMBER_ME") ?>"
              >
                <input class="popup-login__check visually-hidden"
                       type="checkbox"
                       name="USER_REMEMBER"
                       value="Y"
                />
                <span><? echo GetMessage("AUTH_REMEMBER_SHORT") ?></span>
              </label>
						<? } ?>
            <button class="popup-login__button" type="submit">
              Войти
            </button>
						<? if ($arResult["AUTH_SERVICES"]) { ?>
              <span class="popup-login__or">или</span>
              <a class="popup-login__service popup-login__service--vk"
                 href="#">
                <span>Войти с помощью ВК</span>
              </a>

              <!-- не нашел этот компонент и пока захардкодил ссылку выше -->
              <!--            <div class="bx-auth-lbl">-->
              <!--              --><? //= GetMessage("socserv_as_user_form") ?>
              <!--            </div>-->
              <!--			      --><? //
							//			      $APPLICATION->IncludeComponent(
							//				      "bitrix:socserv.auth.form",
							//				      "icons",
							//				      [
							//					      "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
							//					      "SUFFIX"        => "form",
							//				      ],
							//				      $component,
							//				      ["HIDE_ICONS" => "Y"]
							//			      );
							//			      ?>
						<? } ?>
            <!--                    <div class="sci-login__social">-->
            <!--                        <span>Войти через соц.сети: </span>-->
            <!--                        <a href="#" class="sci-login__social-link">-->
            <!--                            <img src="--><? //=SITE_TEMPLATE_PATH?><!--/assets/img/s-instagram.png" alt="">-->
            <!--                        </a>-->
            <!--                        <a href="#" class="sci-login__social-link">-->
            <!--                            <img src="--><? //=SITE_TEMPLATE_PATH?><!--/assets/img/s-telegram.png" alt="">-->
            <!--                        </a>-->
            <!--                        <a href="#" class="sci-login__social-link">-->
            <!--                            <img src="--><? //=SITE_TEMPLATE_PATH?><!--/assets/img/s-facebook.png" alt="">-->
            <!--                        </a>-->
            <!--                        <a href="#" class="sci-login__social-link">-->
            <!--                            <img src="--><? //=SITE_TEMPLATE_PATH?><!--/assets/img/s-vk.png" alt="">-->
            <!--                        </a>-->
            <!--                    </div>-->


            <label class="popup-login__label-check"
                   title="<?= GetMessage("AUTH_REMEMBER_ME") ?>"
            >
              <input class="popup-login__check visually-hidden"
                     type="checkbox"
                     name="popup-login-consent"
                     required
              />
              <span>
              Нажимая «Зарегистрироваться и войти» вы соглашаетесь
              <a target="_blank" href="/public_offer_agreement/">с условиями оферты</a> и
              <a target="_blank" href="/privacy_policy/">политикой конфиденциальности</a>
            </span>
            </label>

            <div class="reg-footer">
							<? if ($arResult["SECURE_AUTH"]) { ?>
                <span class="bx-auth-secure"
                      id="bx_auth_secure<?= $arResult["RND"] ?>"
                      title="<? echo GetMessage("AUTH_SECURE_NOTE") ?>"
                      style="display:none"
                >
                <div class="bx-auth-secure-icon"></div>
              </span>
                <noscript>
                <span class="bx-auth-secure"
                      title="<? echo GetMessage("AUTH_NONSECURE_NOTE") ?>"
                >
                  <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                </span>
                </noscript>
                <script type="text/javascript">
                  document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
                </script>
							<? } ?>
							<? if ($arResult["CAPTCHA_CODE"]) { ?>


								<?
								echo GetMessage("AUTH_CAPTCHA_PROMT") ?>:<br/>
                <input type="hidden" name="captcha_sid" value="<?
								echo $arResult["CAPTCHA_CODE"] ?>"/>
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?
								echo $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA"/><br/><br/>
                <input type="text" name="captcha_word" maxlength="50" value=""/>

							<? } ?>
            </div>
            <a class="popup-login__forget"
               href="<?= $arResult["AUTH_FORGOT_PASSWORD_URL"] ?>"
               rel="nofollow">
							<?= GetMessage("AUTH_FORGOT_PASSWORD_2") ?>
            </a>
          </form>
        </div>

        <div class="popup-login__block popup-login__block--sing-up">
          <form class="popup-login__form"
                name="system_auth_form<?= $arResult["RND"] ?>"
                method="post"
                target="_top"
                action="<?= $arResult["AUTH_URL"] ?>"
          >
            <div class="popup-login__field">
              <label class="popup-login__label" for="sing-up-email">E-mail</label>
              <input class="popup-login__input"
                     type="email"
                     name="USER_LOGIN"
                     id="sing-up-email"
                     placeholder="Введите e-mail"
                     required
              >
            </div>
            <script>
              BX.ready(function() {
                var loginCookie = BX.getCookie("<?=CUtil::JSEscape($arResult["~LOGIN_COOKIE_NAME"])?>");
                if (loginCookie) {
                  var form = document.forms["system_auth_form<?=$arResult["RND"]?>"];
                  var loginInput = form.elements["USER_LOGIN"];
                  loginInput.value = loginCookie;
                }
              });
            </script>
            <div class="popup-login__field">
              <label class="popup-login__label" for="sing-up-password">Пароль</label>
              <input class="popup-login__input"
                     type="password"
                     name="USER_PASSWORD"
                     id="sing-up-password"
                     autocomplete="off"
                     placeholder="Ваш пароль"
                     required
              >
              <div class="errortext">
								<? if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR']) {
									ShowMessage($arResult['ERROR_MESSAGE']);
								} ?>
              </div>
            </div>
						<? if ($arResult["STORE_PASSWORD"] == "Y") { ?>
              <label class="popup-login__label-check popup-login__label-check--remember"
                     title="<?= GetMessage("AUTH_REMEMBER_ME") ?>"
              >
                <input class="popup-login__check visually-hidden"
                       type="checkbox"
                       name="USER_REMEMBER"
                       value="Y"
                />
                <span><? echo GetMessage("AUTH_REMEMBER_SHORT") ?></span>
              </label>
						<? } ?>
            <button class="popup-login__button" type="submit">
              Зарегистрироваться
            </button>
            <span class="popup-login__or">или</span>
            <a class="popup-login__service popup-login__service--vk"
               href="#">
              <span>Войти с помощью ВК</span>
            </a>
            <label class="popup-login__label-check"
                   title="<?= GetMessage("AUTH_REMEMBER_ME") ?>"
            >
              <input class="popup-login__check visually-hidden"
                     type="checkbox"
                     name="popup-login-consent"
                     required
              />
              <span>
              Нажимая «Зарегистрироваться и войти» вы соглашаетесь
              <a target="_blank" href="/public_offer_agreement/">с условиями оферты</a> и
              <a target="_blank" href="/privacy_policy/">политикой конфиденциальности</a>
            </span>
            </label>
          </form>
        </div>

        <div class="advantages">
          <h3 class="advantages__title">Преимущества регистрации:</h3>
          <ul class="advantages__list">
            <li class="advantages__item">Быстрое оформление заказа</li>
            <li class="advantages__item">История и статусы заказов</li>
            <li class="advantages__item">Специальные предложения</li>
          </ul>

          <a class="popup-login__service" href="#">Зарегистрироваться</a>
        </div>
      </div>
    </div>

		<? if ($arResult["AUTH_SERVICES"]) { ?>
			<?
			$APPLICATION->IncludeComponent(
				"bitrix:socserv.auth.form",
				"",
				[
					"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
					"AUTH_URL"      => $arResult["AUTH_URL"],
					"POST"          => $arResult["POST"],
					"POPUP"         => "Y",
					"SUFFIX"        => "form",
				],
				$component,
				["HIDE_ICONS" => "Y"]
			);
			?>
		<? } ?>
	<? } elseif ($arResult["FORM_TYPE"] == "otp") { ?>
    <form name="system_auth_form<?= $arResult["RND"] ?>" method="post" target="_top"
          action="<?= $arResult["AUTH_URL"] ?>">
			<? if ($arResult["BACKURL"] <> '') { ?>
        <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
			<? } ?>
      <input type="hidden" name="AUTH_FORM" value="Y"/>
      <input type="hidden" name="TYPE" value="OTP"/>
			<?
			echo GetMessage("auth_form_comp_otp") ?><br/>
      <input type="text" name="USER_OTP" maxlength="50" value="" size="17" autocomplete="off"/>
			<?
			if ($arResult["CAPTCHA_CODE"]) {
				?>
				<?
				echo GetMessage("AUTH_CAPTCHA_PROMT") ?>:<br/>
        <input type="hidden" name="captcha_sid" value="<?
				echo $arResult["CAPTCHA_CODE"] ?>"/>
        <img src="/bitrix/tools/captcha.php?captcha_sid=<?
				echo $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA"/><br/><br/>
        <input type="text" name="captcha_word" maxlength="50" value=""/>
			<? } ?>
			<?
			if ($arResult["REMEMBER_OTP"] == "Y") {
				?>
        <input type="checkbox" id="OTP_REMEMBER_frm" name="OTP_REMEMBER" value="Y"/>
        <label for="OTP_REMEMBER_frm" title="<?
				echo GetMessage("auth_form_comp_otp_remember_title") ?>"><?
					echo GetMessage("auth_form_comp_otp_remember") ?></label>
			<? } ?>
      <input type="submit" name="Login" value="<?= GetMessage("AUTH_LOGIN_BUTTON") ?>"/>
      <noindex><a href="<?= $arResult["AUTH_LOGIN_URL"] ?>" rel="nofollow"><?
					echo GetMessage("auth_form_comp_auth") ?></a></noindex>
      <br/>
    </form>
	<? } ?>
<? } ?>
