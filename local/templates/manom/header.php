<!DOCTYPE html>
<html lang="ru">
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>
<html class="no-js"> <![endif]-->
<head>
  <!-- Google Tag Manager -->
  <script>(
      function(w, d, s, l, i) {
        w[l] = w[l] || [];
        w[l].push({
          'gtm.start':
            new Date().getTime(), event: 'gtm.js'
        });
        var f = d.getElementsByTagName(s)[0],
          j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
        j.async = true;
        j.src =
          'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
        f.parentNode.insertBefore(j, f);
      }
    )(window, document, 'script', 'dataLayer', 'GTM-MFLXNC9');</script>
  <!-- End Google Tag Manager -->
  <meta name="yandex-verification" content="ffd678aa654fbf92"/>
	<?
	// CJSCore::Init(array("jquery"));
	$APPLICATION->AddHeadScript('https://code.jquery.com/jquery-3.3.1.min.js');
	?>
  <meta charset="utf-8">
  <!-- <base href="/"> -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- 	<meta name="viewport" content="width=device-width, user-scalable=yes">   -->
  <title><? $APPLICATION->ShowTitle() ?></title>
  <meta name="description" content='Онлайн-магазин "MANOM.ru"'>
  <meta name="author" content="">
  <!-- Favicons -->
  <link rel="shortcut icon" href="favicon.png">
  <link rel="icon" href="<?= SITE_TEMPLATE_PATH ?>/assets/img/favicon/favicon.ico">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= SITE_TEMPLATE_PATH ?>/assets/img/favicon/apple-touch-icon-180x180.png">
  <meta property="og:image" content="<?= SITE_TEMPLATE_PATH ?>/assets/img/banner.jpg">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700|Open+Sans:400,600,700&amp;subset=cyrillic-ext" rel="stylesheet">
  <!-- Add fancyBox -->
  <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/assets/css/jquery-ui.css">
  <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/assets/css/jquery.fancybox.min.css"/>
  <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/assets/css/suggestions.min.css"/>
  <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/assets/css/bootstrap-datepicker.css"/>
  <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/assets/css/main.min-2.css">

  <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/assets/js/coffee/pushUpJS/pushUp.css">
  <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/assets/css/custom.css?v=2">
  <link rel="stylesheet" href="<?= SITE_TEMPLATE_PATH ?>/assets/css/fixes.css">
  <link rel="stylesheet"
        href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
        integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU"
        crossorigin="anonymous">
  <script>
    // Picture element HTML5 shiv
    document.createElement("picture");
  </script>
  <script src="<?= SITE_TEMPLATE_PATH ?>/assets/js/picturefill.js" async></script>
  <script type="text/javascript" src="https://vk.com/js/api/share.js?93" charset="windows-1251"></script>
  <script src='https://www.google.com/recaptcha/api.js?render=6LeuEIEUAAAAAFd1nHH6PD8ckNxVwX6p0_6j_Hxr'></script>
  <script src="https://cdn.jsdelivr.net/npm/vue@2.6.10/dist/vue.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/3.0.1/mustache.min.js"></script>
</head>
<? global $USER; ?>
<? $APPLICATION->ShowHead(); ?>
<? if ($USER->IsAdmin()): ?>
	<? $APPLICATION->ShowPanel(); ?>
<? endif; ?>
<?
global $userCityByGeoIP;
$uli = new UserLocation;
$userLocationInfo = $uli->getUserLocationInfo();
$userCityByGeoIP = $userLocationInfo;
?>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript>
  <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MFLXNC9" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<div class="layout">
	<? if ($APPLICATION->GetCurPage() === "/cart/"): ?>
    <header class="header header--cart">
      <div class="container">
        <div class="top-nav">
          <a class="top-nav__logo" href="/">
            <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/logo.svg"
                 class="top-nav__picture"
                 alt="Логотип интернет магазина Manom.ru"
                 width="130"
                 height="23">
          </a>

          <div class="top-nav1__call coll-2">
            <? $APPLICATION->IncludeComponent(
              "bitrix:main.include",
              ".default",
              [
                "PATH"               => "/include/phone.php",
                "COMPONENT_TEMPLATE" => ".default",
                "AREA_FILE_SHOW"     => "file",
                "EDIT_TEMPLATE"      => "",
              ],
              false
            ); ?>
            <button class="top-nav__button-show" type="button"></button>
            <a data-fancybox data-src="#popap-call" href="javascript:;" class="top-nav__request">Заказать звонок</a>
          </div>

          <div class="header__wrapper-top">
            <nav class="header__nav">
              <? $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "top_menu",
                [
                  "ROOT_MENU_TYPE"        => "top",
                  "MAX_LEVEL"             => "1",
                  "CHILD_MENU_TYPE"       => "top",
                  "USE_EXT"               => "Y",
                  "DELAY"                 => "N",
                  "ALLOW_MULTI_SELECT"    => "Y",
                  "MENU_CACHE_TYPE"       => "N",
                  "MENU_CACHE_TIME"       => "3600",
                  "MENU_CACHE_USE_GROUPS" => "Y",
                  "MENU_CACHE_GET_VARS"   => "",
                ]
              ); ?>
            </nav>

            <div class="top-location-line">
              <div id="dnd-location">
                <div class="dnd-location-line" :class="doShowPanel()">
                  <a class="dnd-location-curent user-nav__link user-nav__link--location" @click.stop="doChangeCity()" href="#">
                    <span>{{currentCity}}</span>
                  </a>
                  <transition name="fade">
                    <div class="dnd-location-specify popup-block popup-block--location" v-if="!isInformationStatus">
                      <div class="popup-block__overlay"
                           @click.stop="doChangeCity()">
                      </div>
                      <div class="popup-block__wrapper popup-block__wrapper--location">
                        <div v-if="isConfirmCityVisible">
                          <div class="popup-block__top">
                            <h2 class="popup-block__title">
                              "{{currentCity}}" - это ваш город?
                            </h2>
                            <button class="popup-block__close"
                                    type="button"
                                    aria-label="Закрыть окно"
                                    @click.stop="doChangeCity()">
                            </button>
                          </div>
                          <div class="dnd-location-specify-btn">
                            <button @click="currentCityIsActual()" type="button">Да</button>
                            <button @click.stop="doChangeCity()" type="button">Нет</button>
                          </div>
                        </div>
                        <div v-if="isNotDefinedCityVisible">
                          <div class="popup-block__top">
                            <h2 class="popup-block__title">Ваш город не определен</h2>
                            <button class="popup-block__close"
                                    type="button"
                                    aria-label="Закрыть окно"
                                    @click.stop="doChangeCity()">
                            </button>
                          </div>
                          <div class="dnd-location-specify-btn">
                            <button @click.stop="doChangeCity()" type="button">Выбрать</button>
                          </div>
                        </div>
                        <transition name="fade">
                          <div v-if="isPopupChangeCityVisible">
                            <!-- <p>Выберите город</p> -->
                            <div class="dnd-location-change-city-form">
                              <div class="popup-block__top">
                                <h2 class="popup-block__title">Выбрать город</h2>
                                <button class="popup-block__close"
                                        type="button"
                                        aria-label="Закрыть окно"
                                        @click.stop="doChangeCity()">
                                </button>
                              </div>
                              <div class="popup-block__field popup-block__field--row">
                                <input
                                  class="popup-block__input popup-block__input--search"
                                  type="text"
                                  name="dndLocationChangeCity"
                                  v-model="changeCitySearchLine"
                                  placeholder="Введите название города">
                                <i class="search-block__submit"></i>
                              </div>
                              <ul>
                                <li v-for="cityItem in listOfCity"
                                    @click="changeCity(cityItem)"
                                    class="dnd-location__item"
                                    :class="{'dnd-location__item--active': currentCity === cityItem.title}"
                                >
                                  {{cityItem.title}}
                                </li>
                              </ul>
                            </div>
                          </div>
                        </transition>
                      </div>
                      <!-- <div class="dnd-location-change-city" v-if="isShowPopupCity">
                          <p>Выберите город</p>
                          <div class="dnd-location-change-city-form">
                              <input type="text" name="dndLocationChangeCity" v-model="changeCitySearchLine">
                          </div>
                      </div> -->
                    </div>
                  </transition>
                </div>
              </div>
            </div>
            <script type="text/javascript">
              const LocationDataDND = {
                cityName: "<?=$userLocationInfo['CITY_NAME']?>",
                cityID: "<?=$userLocationInfo['ID']?>",
                specifyInformation: <?=$uli->getUserSpecifyStatus() ? "true" : "false"?>,
                defaultCityList: <?=json_encode($uli->getDefaultListOfCity())?>
              }
            </script>
          </div>
        </div>
      </div>
    </header>
	<? else: ?>
  <header class="header">
    <!-- Рекламная полоса -->
    <!--			<div class="top-ad-line">
				<div class="container">
					<div class="top-ad-line__block">
						<? /* $APPLICATION->IncludeComponent(
							"bitrix:main.include",
							".default",
							[
								"PATH"               => "/include/header-line.php",
								"COMPONENT_TEMPLATE" => ".default",
								"AREA_FILE_SHOW"     => "file",
								"EDIT_TEMPLATE"      => "",
							],
							false
						); */ ?>

						<a href="" class="top-ad-line__close">
							<img src="<? /*= SITE_TEMPLATE_PATH */ ?>/assets/img/top-close.svg" alt="">
						</a>
					</div>
				</div>
			</div>-->

    <div class="header__wrapper">
      <!-- Верхняя навигация -->
      <div class="user-nav">
        <div class="container">
          <div class="user-nav__wrapper">
            <!-- location -->
            <div class="top-location-line">
              <div id="dnd-location">
                <div class="dnd-location-line" :class="doShowPanel()">
                  <a class="dnd-location-curent user-nav__link user-nav__link--location" @click.stop="doChangeCity()" href="#">
                    <span>{{currentCity}}</span>
                  </a>
                  <transition name="fade">
                    <div class="dnd-location-specify popup-block popup-block--location" v-if="!isInformationStatus">
                      <div class="popup-block__overlay"
                           @click.stop="doChangeCity()">
                      </div>
                      <div class="popup-block__wrapper popup-block__wrapper--location">
                        <div v-if="isConfirmCityVisible">
                          <div class="popup-block__top">
                            <h2 class="popup-block__title">
                              "{{currentCity}}" - это ваш город?
                            </h2>
                            <button class="popup-block__close"
                                    type="button"
                                    aria-label="Закрыть окно"
                                    @click.stop="doChangeCity()">
                            </button>
                          </div>
                          <div class="dnd-location-specify-btn">
                            <button @click="currentCityIsActual()" type="button">Да</button>
                            <button @click.stop="doChangeCity()" type="button">Нет</button>
                          </div>
                        </div>
                        <div v-if="isNotDefinedCityVisible">
                          <div class="popup-block__top">
                            <h2 class="popup-block__title">Ваш город не определен</h2>
                            <button class="popup-block__close"
                                    type="button"
                                    aria-label="Закрыть окно"
                                    @click.stop="doChangeCity()">
                            </button>
                          </div>
                          <div class="dnd-location-specify-btn">
                            <button @click.stop="doChangeCity()" type="button">Выбрать</button>
                          </div>
                        </div>
                        <transition name="fade">
                          <div v-if="isPopupChangeCityVisible">
                            <!-- <p>Выберите город</p> -->
                            <div class="dnd-location-change-city-form">
                              <div class="popup-block__top">
                                <h2 class="popup-block__title">Выбрать город</h2>
                                <button class="popup-block__close"
                                        type="button"
                                        aria-label="Закрыть окно"
                                        @click.stop="doChangeCity()">
                                </button>
                              </div>
                              <div class="popup-block__field popup-block__field--row">
                                <input
                                  class="popup-block__input popup-block__input--search"
                                  type="text"
                                  name="dndLocationChangeCity"
                                  v-model="changeCitySearchLine"
                                  placeholder="Введите название города">
                                <i class="search-block__submit"></i>
                              </div>
                              <ul>
                                <li v-for="cityItem in listOfCity"
                                    @click="changeCity(cityItem)"
                                    class="dnd-location__item"
                                    :class="{'dnd-location__item--active': currentCity === cityItem.title}"
                                >
                                  {{cityItem.title}}
                                </li>
                              </ul>
                            </div>
                          </div>
                        </transition>
                      </div>
                      <!-- <div class="dnd-location-change-city" v-if="isShowPopupCity">
                          <p>Выберите город</p>
                          <div class="dnd-location-change-city-form">
                              <input type="text" name="dndLocationChangeCity" v-model="changeCitySearchLine">
                          </div>
                      </div> -->
                    </div>
                  </transition>
                </div>
              </div>
            </div>
            <script type="text/javascript">
              const LocationDataDND = {
                cityName: "<?=$userLocationInfo['CITY_NAME']?>",
                cityID: "<?=$userLocationInfo['ID']?>",
                specifyInformation: <?=$uli->getUserSpecifyStatus() ? "true" : "false"?>,
                defaultCityList: <?=json_encode($uli->getDefaultListOfCity())?>
              }
            </script>
            <!-- /location -->
            <div class="user-nav__block">
							<? $APPLICATION->IncludeComponent(
								"bitrix:menu",
								"top_menu",
								[
									"ROOT_MENU_TYPE"        => "top",
									"MAX_LEVEL"             => "1",
									"CHILD_MENU_TYPE"       => "top",
									"USE_EXT"               => "Y",
									"DELAY"                 => "N",
									"ALLOW_MULTI_SELECT"    => "Y",
									"MENU_CACHE_TYPE"       => "N",
									"MENU_CACHE_TIME"       => "3600",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS"   => "",
								]
							); ?>
            </div>

            <!-- time -->
            <div class="top-nav__time-wrapper">
							<? $APPLICATION->IncludeComponent(
								"bitrix:main.include",
								".default",
								[
									"PATH"               => "/include/time.php",
									"COMPONENT_TEMPLATE" => ".default",
									"AREA_FILE_SHOW"     => "file",
									"EDIT_TEMPLATE"      => "",
								],
								false
							); ?>
            </div>
            <!-- /time -->

            <!-- number -->
            <div class="top-nav__number">
							<? $APPLICATION->IncludeComponent(
								"bitrix:main.include",
								".default",
								[
									"PATH"               => "/include/phone.php",
									"COMPONENT_TEMPLATE" => ".default",
									"AREA_FILE_SHOW"     => "file",
									"EDIT_TEMPLATE"      => "",
								],
								false
							); ?>
              <a data-fancybox data-src="#popap-call" href="javascript:;" class="top-nav__call-request">Заказать звонок</a>
            </div>

            <!-- Всплывающее окно Заказать звонок -->
            <div id="popap-call" class="popup-block popup-block--call">
              <div class="popup-block__wrapper">
                <div class="popup-block__top">
                  <h2 class="popup-block__title">Заказать обратный звонок</h2>
                </div>
                <form action="" method="get">
                  <input type="hidden" name="form_id" value="1">
                  <div class="popup-block__field">
                    <label class="popup-block__label" for="sci-login__name">
                      Имя
                    </label>
                    <input class="popup-block__input"
                           type="text"
                           name="name"
                           id="sci-login__name"
                           placeholder="Ваше имя"
                           required>
                  </div>
                  <div class="popup-block__field">
                    <label class="popup-block__label" for="sci-login__tel">
                      Телефон
                    </label>
                    <input class="popup-block__input"
                           type="tel"
                           name="phone"
                           id="sci-login__tel"
                           placeholder="+7 (___) ___-__-__"
                           required>
                  </div>
                  <button class="popup-block__button" type="submit">
                    Позвоните мне
                  </button>
                </form>
              </div>
            </div>
            <!-- /number -->

          </div>
        </div>
      </div>

      <div class="top-nav">
        <div class="container">
          <div class="top-nav__block">
            <button class="top-nav__toggle" type="button" aria-label="Открыть меню">
              <span></span>
            </button>
            <div class="top-nav__wrapper">
              <div class="top-nav__layout"></div>
              <nav class="top-nav__menu">
                <a href="/" class="top-nav__logo">
                  <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/logo.svg" alt="Логотип интернет магазина Manom.ru" width="129" height="23">
                </a>
                  <? $APPLICATION->IncludeComponent(
                      "bitrix:menu",
                      "mob_sections_with_submenu",
                      [
                          "ROOT_MENU_TYPE"        => "sections",
                          "MAX_LEVEL"             => "1",
                          "CHILD_MENU_TYPE"       => "top",
                          "USE_EXT"               => "Y",
                          "DELAY"                 => "N",
                          "ALLOW_MULTI_SELECT"    => "Y",
                          "MENU_CACHE_TYPE"       => "N",
                          "MENU_CACHE_TIME"       => "3600",
                          "MENU_CACHE_USE_GROUPS" => "Y",
                          "MENU_CACHE_GET_VARS"   => "",
                      ]
                  ); ?>
                <ul class="top-nav__list top-nav__list--user">
                    <li>
                        <a class="top-nav__link top-nav__link--compare"
                           <?= getProdListFavoritAndCompare('UF_COMPARE_ID') ? "href='/catalog/compare/'" : "" ?>>Сравнение</a>
                    </li>
                  <li>
                    <!-- location -->
                    <a class="top-nav__link top-nav__link--location" href="#">Город</a>
                    <!-- /location -->
                  </li>
                </ul>

                <p class="top-nav__time">Ежедневно 11:00 - 19:00</p>
                <div class="top-nav__number">
									<? $APPLICATION->IncludeComponent(
										"bitrix:main.include",
										".default",
										[
											"PATH"               => "/include/phone.php",
											"COMPONENT_TEMPLATE" => ".default",
											"AREA_FILE_SHOW"     => "file",
											"EDIT_TEMPLATE"      => "",
										],
										false
									); ?>
                  <a data-fancybox
                     data-src="#popap-call"
                     href="javascript:;"
                     class="top-nav__call-request">
                    Заказать звонок
                  </a>
                </div>
              </nav>
                <? $APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "mob_sections_submenu",
                    [
                        "ROOT_MENU_TYPE"        => "sections",
                        "MAX_LEVEL"             => "1",
                        "CHILD_MENU_TYPE"       => "top",
                        "USE_EXT"               => "Y",
                        "DELAY"                 => "N",
                        "ALLOW_MULTI_SELECT"    => "Y",
                        "MENU_CACHE_TYPE"       => "N",
                        "MENU_CACHE_TIME"       => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS"   => "",
                    ]
                ); ?>
            </div>

            <a href="/" class="top-nav__logo-link">
              <picture>
                <source media="(min-width: 768px)" srcset="<?= SITE_TEMPLATE_PATH ?>/assets/img/logo.svg">
                <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/logo-mobile.svg" alt="Логотип интернет магазина Manom.ru">
              </picture>
            </a>

            <!--            <div class="top-menu">-->
            <!--							--><? // $APPLICATION->IncludeComponent(
						//								"bitrix:menu",
						//								"top_menu",
						//								[
						//									"ROOT_MENU_TYPE"        => "top",
						//									"MAX_LEVEL"             => "1",
						//									"CHILD_MENU_TYPE"       => "top",
						//									"USE_EXT"               => "Y",
						//									"DELAY"                 => "N",
						//									"ALLOW_MULTI_SELECT"    => "Y",
						//									"MENU_CACHE_TYPE"       => "N",
						//									"MENU_CACHE_TIME"       => "3600",
						//									"MENU_CACHE_USE_GROUPS" => "Y",
						//									"MENU_CACHE_GET_VARS"   => "",
						//								]
						//							); ?>
            <!--            </div>-->


            <!--			  	<div id="popap-login" class="popap-login">-->
            <!--			  		<h3 class="sci-login__title">Войти в существующий аккаунт</h3>-->
            <!--						<form class="sci-login__form">-->
            <!--							<label class="sci-login__label" for="sci-login__email">E-mail</label>-->
            <!--							<input type="email" name="email" id="sci-login__email" class="sci-login__input" placeholder="Ваш e-mail" required>-->
            <!--							<label class="sci-login__label" for="sci-login__password">Пароль</label>-->
            <!--							<input type="password" name="password" id="sci-login__password" class="sci-login__input" placeholder="Ваш пароль" required>-->
            <!--							<div class="sci-login__social">-->
            <!--								<span>Войти через соц.сети: </span>-->
            <!--								<a href="#" class="sci-login__social-link">-->
            <!--									<img src="--><? //=SITE_TEMPLATE_PATH?><!--/assets/img/s-instagram.png" alt="">-->
            <!--								</a>-->
            <!--								<a href="#" class="sci-login__social-link">-->
            <!--									<img src="--><? //=SITE_TEMPLATE_PATH?><!--/assets/img/s-telegram.png" alt="">-->
            <!--								</a>-->
            <!--								<a href="#" class="sci-login__social-link">-->
            <!--									<img src="--><? //=SITE_TEMPLATE_PATH?><!--/assets/img/s-facebook.png" alt="">-->
            <!--								</a>-->
            <!--								<a href="#" class="sci-login__social-link">-->
            <!--									<img src="--><? //=SITE_TEMPLATE_PATH?><!--/assets/img/s-vk.png" alt="">-->
            <!--								</a>-->
            <!--							</div>-->
            <!--							<button class="sci-login__button">Войти</button>-->
            <!--							<a href="#" class="sci-login__forgot">Забыли пароль?</a>-->
            <!--						</form>-->
            <!--			  	</div>-->
              <? $APPLICATION->IncludeComponent(
                  "bitrix:menu",
                  "menu_sections",
                  [
                      "ROOT_MENU_TYPE"        => "sections",
                      "MAX_LEVEL"             => "1",
                      "CHILD_MENU_TYPE"       => "top",
                      "USE_EXT"               => "Y",
                      "DELAY"                 => "N",
                      "ALLOW_MULTI_SELECT"    => "Y",
                      "MENU_CACHE_TYPE"       => "N",
                      "MENU_CACHE_TIME"       => "3600",
                      "MENU_CACHE_USE_GROUPS" => "Y",
                      "MENU_CACHE_GET_VARS"   => "",
                  ]
              ); ?>
            <div class="top-personal">

              <a class="top-personal__link top-personal__link--search"
                 href="#">
                <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/icons/search.svg" alt="Иконка поиска" width="15" height="15">
              </a>
              <div class="popup-block popup-block--search">
                <div class="popup-block__overlay"></div>
                <div class="popup-block__wrapper">
                  <div class="container">
                    <div class="popup-block__top">
                      <h2 class="popup-block__title">Поиск</h2>
                      <button class="popup-block__close" type="button" aria-label="Закрыть окно"></button>
                    </div>
                    <div class="search-block">
											<? $APPLICATION->IncludeComponent(
												"bitrix:search.form",
												"search",
												[
													"USE_SUGGEST"        => "N",
													"PAGE"               => "#SITE_DIR#search/index.php",
													"COMPONENT_TEMPLATE" => "search",
												],
												false
											); ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="top-personal__block">
								<? if ($USER->IsAuthorized()) { ?>
                  <a class="top-personal__link js-user-block"
                     href="#"
                     aria-label="Профиль">
                    <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/icons/icon-user.svg"
                         alt="Иконка пользователя"
                         width="18" height="18">
                  </a>
                  <div class="personal-preview personal-preview--user">
                    <ul class="top-personal__list">
                      <li>
                        <a class="top-personal__user-link top-personal__user-link--profile"
                           href="/user/profile.php">
                          Личный кабинет
                        </a>
                      </li>
                      <li>
                        <a class="top-personal__user-link top-personal__user-link--history" href="/user/history.php">
                          История заказов
                        </a>
                      </li>
                      <li>
                        <form class="sci-login__form"
                              action="/auth/?bitrix_include_areas=N">
                          <input type="hidden"
                                 name="bitrix_include_areas"
                                 value="N">
                          <input type="hidden"
                                 name="logout"
                                 value="yes">
                          <button class="top-personal__user-link top-personal__user-link--out"
                                  type="submit"
                                  name="logout_butt">
                            Выйти
                          </button>
                        </form>
                      </li>
                    </ul>
                  </div>
								<? } else { ?>
                  <a class="top-personal__link"
                     data-fancybox
                     data-src="#popap-login"
                     href="javascript:;"
                     aria-label="Вход">
                    <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/icons/icon-user.svg" alt="Иконка пользователя" width="18" height="18">
                  </a>
								<? } ?>
              </div>
							<?
							global $customFilt;
							$favList = getProdListFavoritAndCompare('UF_COMPARE_ID');
							$customFilt = ['ID' => $favList];
							?>
							<? if (!$favList): ?>
								<? if ($_REQUEST['AJAX_MIN_COMPARE'] == 'Y'): ?>
									<? $APPLICATION->RestartBuffer(); ?>
								<? endif; ?>
                <div class="top-personal__block top-personal__block--compare">
                  <a class="top-personal__link top-personal__link--compare" id="mini_compare_header_counter">
                    <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/icons/compare.svg" alt="Иконка сравнения" width="16" height="15">
                    <!--                    <span class="top-count">0</span>-->
                  </a>
                  <div class="preview-heart preview-heart--empty personal-preview" id="mini_compare_header">
                    <p class="preview-heart-not-text">Нет товара</p>
                  </div>
                </div>
								<? if ($_REQUEST['AJAX_MIN_COMPARE'] == 'Y'): ?>
									<? die(); ?>
								<? endif; ?>
							<? else: ?>
								<? $APPLICATION->IncludeComponent(
									"bitrix:catalog.section",
									"compare_mini",
									[
										"ACTION_VARIABLE"                 => "action",
										"ADD_PICT_PROP"                   => "MORE_PHOTO",
										"ADD_PROPERTIES_TO_BASKET"        => "Y",
										"ADD_SECTIONS_CHAIN"              => "N",
										"ADD_TO_BASKET_ACTION"            => "ADD",
										"AJAX_MODE"                       => "N",
										"AJAX_OPTION_ADDITIONAL"          => "",
										"AJAX_OPTION_HISTORY"             => "N",
										"AJAX_OPTION_JUMP"                => "N",
										"AJAX_OPTION_STYLE"               => "Y",
										"BACKGROUND_IMAGE"                => "-",
										"BASKET_URL"                      => "/personal/basket.php",
										"BRAND_PROPERTY"                  => "-",
										"BROWSER_TITLE"                   => "-",
										"CACHE_FILTER"                    => "N",
										"CACHE_GROUPS"                    => "N",
										"CACHE_TIME"                      => "36000000",
										"CACHE_TYPE"                      => "A",
										"COMPATIBLE_MODE"                 => "Y",
										"CONVERT_CURRENCY"                => "Y",
										"CURRENCY_ID"                     => "RUB",
										"CUSTOM_FILTER"                   => "",
										"DATA_LAYER_NAME"                 => "dataLayer",
										"DETAIL_URL"                      => "",
										"DISABLE_INIT_JS_IN_COMPONENT"    => "N",
										"DISCOUNT_PERCENT_POSITION"       => "bottom-right",
										"DISPLAY_BOTTOM_PAGER"            => "N",
										"DISPLAY_TOP_PAGER"               => "N",
										"ELEMENT_SORT_FIELD"              => 'ID',//'CATALOG_PRICE_1',//$arParams["ELEMENT_SORT_FIELD"],
										"ELEMENT_SORT_ORDER"              => 'asc',//'DESC',//$arParams["ELEMENT_SORT_ORDER"],
										"ENLARGE_PRODUCT"                 => "PROP",
										"ENLARGE_PROP"                    => "-",
										"FILTER_NAME"                     => "customFilt",
										"HIDE_NOT_AVAILABLE"              => "N",
										"HIDE_NOT_AVAILABLE_OFFERS"       => "N",
										"IBLOCK_ID"                       => "7",
										"IBLOCK_TYPE"                     => "catalog",
										"INCLUDE_SUBSECTIONS"             => "Y",
										"LABEL_PROP"                      => [
										],
										"LABEL_PROP_MOBILE"               => "",
										"LABEL_PROP_POSITION"             => "top-left",
										"LAZY_LOAD"                       => "Y",
										"LINE_ELEMENT_COUNT"              => "3",
										"LOAD_ON_SCROLL"                  => "N",
										"MESSAGE_404"                     => "",
										"MESS_BTN_ADD_TO_BASKET"          => "В корзину",
										"MESS_BTN_BUY"                    => "Купить",
										"MESS_BTN_DETAIL"                 => "Подробнее",
										"MESS_BTN_LAZY_LOAD"              => "Показать ещё",
										"MESS_BTN_SUBSCRIBE"              => "Подписаться",
										"MESS_NOT_AVAILABLE"              => "Нет в наличии",
										"META_DESCRIPTION"                => "-",
										"META_KEYWORDS"                   => "-",
										"OFFERS_PROPERTY_CODE"            => [
											0 => "COLOR_REF",
											1 => "SIZES_SHOES",
											2 => "SIZES_CLOTHES",
											3 => "",
											4 => "",
										],
										"OFFERS_LIMIT"                    => "5",
										"OFFERS_SORT_FIELD"               => "sort",
										"OFFERS_SORT_FIELD2"              => "id",
										"OFFERS_SORT_ORDER"               => "asc",
										"OFFERS_SORT_ORDER2"              => "desc",
										"OFFER_ADD_PICT_PROP"             => "MORE_PHOTO",
										"OFFER_TREE_PROPS"                => [
											0 => "COLOR_REF",
											1 => "SIZES_SHOES",
											2 => "SIZES_CLOTHES",
										],
										"PAGER_BASE_LINK_ENABLE"          => "N",
										"PAGER_DESC_NUMBERING"            => "N",
										"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
										"PAGER_SHOW_ALL"                  => "N",
										"PAGER_SHOW_ALWAYS"               => "N",
										"PAGER_TEMPLATE"                  => "catalog_section",
										"PAGER_TITLE"                     => "Товары",
										"PAGE_ELEMENT_COUNT"              => 9999,//3,//$arParams["PAGE_ELEMENT_COUNT"],
										"PARTIAL_PRODUCT_PROPERTIES"      => "N",
										"PRICE_CODE"                      => [
											0 => "Розничная",
										],
										"PRICE_VAT_INCLUDE"               => "Y",
										"PRODUCT_BLOCKS_ORDER"            => "price,props,sku,quantityLimit,quantity,buttons,compare",
										"PRODUCT_DISPLAY_MODE"            => "Y",
										"PRODUCT_ID_VARIABLE"             => "id",
										"PRODUCT_PROPERTIES"              => [
										],
										"PRODUCT_PROPS_VARIABLE"          => "prop",
										"PRODUCT_QUANTITY_VARIABLE"       => "",
										"PRODUCT_ROW_VARIANTS"            => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
										"PRODUCT_SUBSCRIPTION"            => "N",
										"PROPERTY_CODE"                   => [
											0 => "",
											1 => "NEWPRODUCT",
											2 => "",
										],
										"PROPERTY_CODE_MOBILE"            => [
										],
										"RCM_PROD_ID"                     => $_REQUEST["PRODUCT_ID"],
										"RCM_TYPE"                        => "personal",
										"SECTION_CODE"                    => "",
										"SECTION_ID"                      => "",
										"SECTION_ID_VARIABLE"             => "SECTION_ID",
										"SECTION_URL"                     => "",
										"SECTION_USER_FIELDS"             => [
											0 => "",
											1 => "",
										],
										"SEF_MODE"                        => "Y",
										"SET_BROWSER_TITLE"               => "N",
										"SET_LAST_MODIFIED"               => "N",
										"SET_META_DESCRIPTION"            => "N",
										"SET_META_KEYWORDS"               => "N",
										"SET_STATUS_404"                  => "N",
										"SET_TITLE"                       => "N",
										"SHOW_404"                        => "N",
										"SHOW_ALL_WO_SECTION"             => "N",
										"SHOW_CLOSE_POPUP"                => "N",
										"SHOW_DISCOUNT_PERCENT"           => "N",
										"SHOW_FROM_SECTION"               => "N",
										"SHOW_MAX_QUANTITY"               => "N",
										"SHOW_OLD_PRICE"                  => "N",
										"SHOW_PRICE_COUNT"                => "1",
										"SHOW_SLIDER"                     => "Y",
										"SLIDER_INTERVAL"                 => "3000",
										"SLIDER_PROGRESS"                 => "N",
										"TEMPLATE_THEME"                  => "blue",
										"USE_ENHANCED_ECOMMERCE"          => "Y",
										"USE_MAIN_ELEMENT_SECTION"        => "N",
										"USE_PRICE_COUNT"                 => "N",
										"USE_PRODUCT_QUANTITY"            => "N",
										"COMPONENT_TEMPLATE"              => "favorite",
										"DISPLAY_COMPARE"                 => "N",
									],
									false
								); ?>
							<? endif; ?>
							<?
							global $customFilt;
							$favList = getProdListFavoritAndCompare('UF_FAVORITE_ID');
							$customFilt = ['ID' => $favList];
							?>
							<? if (!$favList): ?>
								<? if ($_REQUEST['AJAX_MIN_FAVORITE'] == 'Y'): ?>
									<? $APPLICATION->RestartBuffer(); ?>
								<? endif; ?>
                <div class="top-personal__block">
                  <a class="top-personal__link" id="mini_favorite_header_counter">
                    <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/icons/heart.svg"
                         alt="Иконка избранного"
                         width="17"
                         height="15">
                    <!--                    <span class="top-count">0</span>-->
                  </a>
                  <div class="preview-heart preview-heart--empty personal-preview" id="mini_favorite_header">
                    <p class="preview-heart-not-text">Нет товара</p>
                  </div>
                </div>
								<? if ($_REQUEST['AJAX_MIN_CART'] == 'Y'): ?>
									<? die(); ?>
								<? endif; ?>
							<? else: ?>
								<? $APPLICATION->IncludeComponent(
									"bitrix:catalog.section",
									"favorite_mini",
									[
										"ACTION_VARIABLE"                 => "action",
										"ADD_PICT_PROP"                   => "MORE_PHOTO",
										"ADD_PROPERTIES_TO_BASKET"        => "Y",
										"ADD_SECTIONS_CHAIN"              => "N",
										"ADD_TO_BASKET_ACTION"            => "ADD",
										"AJAX_MODE"                       => "N",
										"AJAX_OPTION_ADDITIONAL"          => "",
										"AJAX_OPTION_HISTORY"             => "N",
										"AJAX_OPTION_JUMP"                => "N",
										"AJAX_OPTION_STYLE"               => "Y",
										"BACKGROUND_IMAGE"                => "-",
										"BASKET_URL"                      => "/personal/basket.php",
										"BRAND_PROPERTY"                  => "-",
										"BROWSER_TITLE"                   => "-",
										"CACHE_FILTER"                    => "N",
										"CACHE_GROUPS"                    => "N",
										"CACHE_TIME"                      => "36000000",
										"CACHE_TYPE"                      => "A",
										"COMPATIBLE_MODE"                 => "Y",
										"CONVERT_CURRENCY"                => "Y",
										"CURRENCY_ID"                     => "RUB",
										"CUSTOM_FILTER"                   => "",
										"DATA_LAYER_NAME"                 => "dataLayer",
										"DETAIL_URL"                      => "",
										"DISABLE_INIT_JS_IN_COMPONENT"    => "N",
										"DISCOUNT_PERCENT_POSITION"       => "bottom-right",
										"DISPLAY_BOTTOM_PAGER"            => "N",
										"DISPLAY_TOP_PAGER"               => "N",
										"ELEMENT_SORT_FIELD"              => 'ID',//'CATALOG_PRICE_1',//$arParams["ELEMENT_SORT_FIELD"],
										"ELEMENT_SORT_ORDER"              => 'asc',//'DESC',//$arParams["ELEMENT_SORT_ORDER"],
										"ENLARGE_PRODUCT"                 => "PROP",
										"ENLARGE_PROP"                    => "-",
										"FILTER_NAME"                     => "customFilt",
										"HIDE_NOT_AVAILABLE"              => "N",
										"HIDE_NOT_AVAILABLE_OFFERS"       => "N",
										"IBLOCK_ID"                       => "7",
										"IBLOCK_TYPE"                     => "catalog",
										"INCLUDE_SUBSECTIONS"             => "Y",
										"LABEL_PROP"                      => [
										],
										"LABEL_PROP_MOBILE"               => "",
										"LABEL_PROP_POSITION"             => "top-left",
										"LAZY_LOAD"                       => "Y",
										"LINE_ELEMENT_COUNT"              => "3",
										"LOAD_ON_SCROLL"                  => "N",
										"MESSAGE_404"                     => "",
										"MESS_BTN_ADD_TO_BASKET"          => "В корзину",
										"MESS_BTN_BUY"                    => "Купить",
										"MESS_BTN_DETAIL"                 => "Подробнее",
										"MESS_BTN_LAZY_LOAD"              => "Показать ещё",
										"MESS_BTN_SUBSCRIBE"              => "Подписаться",
										"MESS_NOT_AVAILABLE"              => "Нет в наличии",
										"META_DESCRIPTION"                => "-",
										"META_KEYWORDS"                   => "-",
										"OFFERS_PROPERTY_CODE"            => [
											0 => "COLOR_REF",
											1 => "SIZES_SHOES",
											2 => "SIZES_CLOTHES",
											3 => "",
											4 => "",
										],
										"OFFERS_LIMIT"                    => "5",
										"OFFERS_SORT_FIELD"               => "sort",
										"OFFERS_SORT_FIELD2"              => "id",
										"OFFERS_SORT_ORDER"               => "asc",
										"OFFERS_SORT_ORDER2"              => "desc",
										"OFFER_ADD_PICT_PROP"             => "MORE_PHOTO",
										"OFFER_TREE_PROPS"                => [
											0 => "COLOR_REF",
											1 => "SIZES_SHOES",
											2 => "SIZES_CLOTHES",
										],
										"PAGER_BASE_LINK_ENABLE"          => "N",
										"PAGER_DESC_NUMBERING"            => "N",
										"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
										"PAGER_SHOW_ALL"                  => "N",
										"PAGER_SHOW_ALWAYS"               => "N",
										"PAGER_TEMPLATE"                  => "catalog_section",
										"PAGER_TITLE"                     => "Товары",
										"PAGE_ELEMENT_COUNT"              => 9999,//3,//$arParams["PAGE_ELEMENT_COUNT"],
										"PARTIAL_PRODUCT_PROPERTIES"      => "N",
										"PRICE_CODE"                      => [
											0 => "Розничная",
										],
										"PRICE_VAT_INCLUDE"               => "Y",
										"PRODUCT_BLOCKS_ORDER"            => "price,props,sku,quantityLimit,quantity,buttons,compare",
										"PRODUCT_DISPLAY_MODE"            => "Y",
										"PRODUCT_ID_VARIABLE"             => "id",
										"PRODUCT_PROPERTIES"              => [
										],
										"PRODUCT_PROPS_VARIABLE"          => "prop",
										"PRODUCT_QUANTITY_VARIABLE"       => "",
										"PRODUCT_ROW_VARIANTS"            => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",
										"PRODUCT_SUBSCRIPTION"            => "N",
										"PROPERTY_CODE"                   => [
											0 => "",
											1 => "NEWPRODUCT",
											2 => "",
										],
										"PROPERTY_CODE_MOBILE"            => [
										],
										"RCM_PROD_ID"                     => $_REQUEST["PRODUCT_ID"],
										"RCM_TYPE"                        => "personal",
										"SECTION_CODE"                    => "",
										"SECTION_ID"                      => "",
										"SECTION_ID_VARIABLE"             => "SECTION_ID",
										"SECTION_URL"                     => "",
										"SECTION_USER_FIELDS"             => [
											0 => "",
											1 => "",
										],
										"SEF_MODE"                        => "Y",
										"SET_BROWSER_TITLE"               => "N",
										"SET_LAST_MODIFIED"               => "N",
										"SET_META_DESCRIPTION"            => "N",
										"SET_META_KEYWORDS"               => "N",
										"SET_STATUS_404"                  => "N",
										"SET_TITLE"                       => "N",
										"SHOW_404"                        => "N",
										"SHOW_ALL_WO_SECTION"             => "N",
										"SHOW_CLOSE_POPUP"                => "N",
										"SHOW_DISCOUNT_PERCENT"           => "N",
										"SHOW_FROM_SECTION"               => "N",
										"SHOW_MAX_QUANTITY"               => "N",
										"SHOW_OLD_PRICE"                  => "N",
										"SHOW_PRICE_COUNT"                => "1",
										"SHOW_SLIDER"                     => "Y",
										"SLIDER_INTERVAL"                 => "3000",
										"SLIDER_PROGRESS"                 => "N",
										"TEMPLATE_THEME"                  => "blue",
										"USE_ENHANCED_ECOMMERCE"          => "Y",
										"USE_MAIN_ELEMENT_SECTION"        => "N",
										"USE_PRICE_COUNT"                 => "N",
										"USE_PRODUCT_QUANTITY"            => "N",
										"COMPONENT_TEMPLATE"              => "favorite",
										"DISPLAY_COMPARE"                 => "N",
									],
									false
								); ?>
							<? endif; ?>
							<? $APPLICATION->IncludeComponent(
								"bitrix:sale.basket.basket.line",
								"monom",
								[
									"HIDE_ON_BASKET_PAGES" => "Y",  // Не показывать на страницах корзины и оформления заказа
									"PATH_TO_BASKET"       => SITE_DIR . "personal/cart/",  // Страница корзины
									"PATH_TO_ORDER"        => SITE_DIR . "personal/order/make/",  // Страница оформления заказа
									"PATH_TO_PERSONAL"     => SITE_DIR . "personal/",  // Страница персонального раздела
									"PATH_TO_PROFILE"      => SITE_DIR . "personal/",  // Страница профиля
									"PATH_TO_REGISTER"     => SITE_DIR . "login/",  // Страница регистрации
									"POSITION_FIXED"       => "Y",  // Отображать корзину поверх шаблона
									"POSITION_HORIZONTAL"  => "right",  // Положение по горизонтали
									"POSITION_VERTICAL"    => "top",  // Положение по вертикали
									"SHOW_AUTHOR"          => "Y",  // Добавить возможность авторизации
									"SHOW_DELAY"           => "N",  // Показывать отложенные товары
									"SHOW_EMPTY_VALUES"    => "Y",  // Выводить нулевые значения в пустой корзине
									"SHOW_IMAGE"           => "Y",  // Выводить картинку товара
									"SHOW_NOTAVAIL"        => "N",  // Показывать товары, недоступные для покупки
									"SHOW_NUM_PRODUCTS"    => "Y",  // Показывать количество товаров
									"SHOW_PERSONAL_LINK"   => "N",  // Отображать персональный раздел
									"SHOW_PRICE"           => "Y",  // Выводить цену товара
									"SHOW_PRODUCTS"        => "Y",  // Показывать список товаров
									"SHOW_SUMMARY"         => "Y",  // Выводить подытог по строке
									"SHOW_TOTAL_PRICE"     => "Y",  // Показывать общую сумму по товарам
								],
								false
							); ?>
            </div>
          </div>
            <? $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "mob_sections_without_submenu",
                [
                    "ROOT_MENU_TYPE"        => "sections",
                    "MAX_LEVEL"             => "1",
                    "CHILD_MENU_TYPE"       => "top",
                    "USE_EXT"               => "Y",
                    "DELAY"                 => "N",
                    "ALLOW_MULTI_SELECT"    => "Y",
                    "MENU_CACHE_TYPE"       => "N",
                    "MENU_CACHE_TIME"       => "3600",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS"   => "",
                ]
            ); ?>
        </div>
      </div>


      <!-- Верхняя навигация 1 -->
      <!--      <div class="top-nav1">-->
      <!--        <div class="container">-->
      <!--          <div class="row top-nav1__block">-->
      <!--            <a href="/" class="top-nav1__logo">-->
      <!--              <img src="--><? //= SITE_TEMPLATE_PATH ?><!--/assets/img/logo.svg" class="top-nav1__logo-desk" alt="">-->
      <!--              <img src="--><? //= SITE_TEMPLATE_PATH ?><!--/assets/img/a.jpg" class="top-nav1__logo-mob" alt="">-->
      <!--            </a>-->

      <!-- SEARCH -->
      <!--						--><? // $APPLICATION->IncludeComponent(
			//							"bitrix:search.form",
			//							"search",
			//							[
			//								"USE_SUGGEST"        => "N",
			//								"PAGE"               => "#SITE_DIR#search/index.php",
			//								"COMPONENT_TEMPLATE" => "search",
			//							],
			//							false
			//						); ?>
      <!-- /SEARCH -->


      <!--          </div>-->
      <!--        </div>-->
      <!--      </div>-->
      <!-- Верхняя навигация 2 -->
      <!--      <div class="top-nav2">-->
      <!--        <div class="container">-->
      <!--          <div class="top-nav2__block">-->
      <!--						--><? // $APPLICATION->IncludeComponent(
			//							"bitrix:catalog.section.list",
			//							"main-menu",
			//							[
			//								"VIEW_MODE"           => "TEXT",
			//								"SHOW_PARENT_NAME"    => "Y",
			//								"IBLOCK_TYPE"         => "catalog",
			//								"IBLOCK_ID"           => "6",
			//								"SECTION_ID"          => $_REQUEST["SECTION_ID"],
			//								"SECTION_CODE"        => "",
			//								"SECTION_URL"         => "",
			//								"COUNT_ELEMENTS"      => "Y",
			//								"TOP_DEPTH"           => "2",
			//								"SECTION_FIELDS"      => "",
			//								"SECTION_USER_FIELDS" => "",
			//								"ADD_SECTIONS_CHAIN"  => "Y",
			//								"CACHE_TYPE"          => "A",
			//								"CACHE_TIME"          => "36000000",
			//								"CACHE_NOTES"         => "",
			//								"CACHE_GROUPS"        => "Y",
			//							]
			//						); ?>
      <!--          </div>-->
      <!--        </div>-->
      <!--      </div>-->
      <!--			--><? //
			//			$page = $APPLICATION->GetCurPage();
			//			if ($page == '/') { ?>
      <!--					<div class="top-nav2 mobile">-->
      <!--						<div class="container">-->
      <!--							<div class="top-nav2__block">-->
      <!--								--><? // $APPLICATION->IncludeComponent(
			//									"bitrix:catalog.section.list",
			//									"main-menu",
			//									[
			//										"VIEW_MODE"           => "TEXT",
			//										"SHOW_PARENT_NAME"    => "Y",
			//										"IBLOCK_TYPE"         => "catalog",
			//										"IBLOCK_ID"           => "6",
			//										"SECTION_ID"          => $_REQUEST["SECTION_ID"],
			//										"SECTION_CODE"        => "",
			//										"SECTION_URL"         => "",
			//										"COUNT_ELEMENTS"      => "Y",
			//										"TOP_DEPTH"           => "2",
			//										"SECTION_FIELDS"      => "",
			//										"SECTION_USER_FIELDS" => "",
			//										"ADD_SECTIONS_CHAIN"  => "Y",
			//										"CACHE_TYPE"          => "A",
			//										"CACHE_TIME"          => "36000000",
			//										"CACHE_NOTES"         => "",
			//										"CACHE_GROUPS"        => "Y",
			//									]
			//								); ?>
      <!--							</div>-->
      <!--						</div>-->
      <!--					</div>-->
      <!--			--><? // } ?>

      <!-- Всплывающее окно Регистрация -->
      <!--      <div id="popap-reg" class="popap-login">-->
      <!--        <h3 class="sci-login__title">Регистрация аккаунта</h3>-->
      <!--        <form class="sci-login__form">-->
      <!--          <label class="sci-login__label" for="sci-reg__email">E-mail</label>-->
      <!--          <input type="email" name="email" id="sci-reg__email" class="sci-login__input" placeholder="Ваш e-mail" required>-->
      <!--          <label class="sci-login__label" for="sci-reg__password">Пароль</label>-->
      <!--          <input type="password" name="password" id="sci-reg__password" class="sci-login__input" placeholder="Ваш пароль" required>-->
      <!--          <div class="sci-login__social">-->
      <!--            <span>Регистрация через соц.сети: </span>-->
      <!--            <a href="#" class="sci-login__social-link">-->
      <!--              <img src="--><? //= SITE_TEMPLATE_PATH ?><!--/assets/img/s-instagram.png" alt="">-->
      <!--            </a>-->
      <!--            <a href="#" class="sci-login__social-link">-->
      <!--              <img src="--><? //= SITE_TEMPLATE_PATH ?><!--/assets/img/s-telegram.png" alt="">-->
      <!--            </a>-->
      <!--            <a href="#" class="sci-login__social-link">-->
      <!--              <img src="--><? //= SITE_TEMPLATE_PATH ?><!--/assets/img/s-facebook.png" alt="">-->
      <!--            </a>-->
      <!--            <a href="#" class="sci-login__social-link">-->
      <!--              <img src="--><? //= SITE_TEMPLATE_PATH ?><!--/assets/img/s-vk.png" alt="">-->
      <!--            </a>-->
      <!--          </div>-->
      <!--          <button class="sci-login__button">Регистрация</button>-->
      <!--        </form>-->
      <!--      </div>-->
      <!-- /Всплывающее окно Регистрация -->

      <!-- Всплывающее окно Логин -->
			<? $APPLICATION->IncludeComponent(
				"bitrix:system.auth.form",
				"popup",
				[
					"REGISTER_URL"        => "/auth/registration.php",
					"FORGOT_PASSWORD_URL" => "/auth/foget.php",
					"PROFILE_URL"         => "/user/index.php",
					"SHOW_ERRORS"         => "Y",
					"COMPONENT_TEMPLATE"  => "auth-cart",
				],
				false
			); ?>
      <!-- /Всплывающее окно Логин -->

    </div>
  </header>
<? endif; ?>