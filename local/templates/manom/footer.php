            <?php if ($APPLICATION->GetCurPage() !== '/cart/'): ?>
                <!-- Footer -->
                <footer class="footer">
                    <div class="container">

                        <?php /*
                        <div class="footer-top">
                            <div class="subscription">
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:subscribe.news',
                                    'footer_subscribe',
                                    Array(
                                        'SITE_ID' => 's1',
                                        'IBLOCK_TYPE' => 'articles',
                                        'ID' => '1',
                                        'SORT_BY' => 'ACTIVE_FROM',
                                        'SORT_ORDER' => 'DESC',
                                    )
                                ); ?>
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:subscribe.form',
                                    'subs_footer',
                                    Array(
                                        'USE_PERSONALIZATION' => 'Y',
                                        'PAGE' => '#SITE_DIR#subscribe/',
                                        'SHOW_HIDDEN' => 'Y',
                                        'CACHE_TYPE' => 'A',
                                        'CACHE_TIME' => '3600',
                                    ),
                                    false
                                ); ?>
                            </div>
                            <div class="social">
                                <span>Будь на связи с нами</span>
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:news.list',
                                    'social_footer',
                                    Array(
                                        'DISPLAY_DATE' => 'Y',
                                        'DISPLAY_NAME' => 'Y',
                                        'DISPLAY_PICTURE' => 'Y',
                                        'DISPLAY_PREVIEW_TEXT' => 'Y',
                                        'AJAX_MODE' => 'N',
                                        'IBLOCK_TYPE' => 'content',
                                        'IBLOCK_ID' => '4',
                                        'NEWS_COUNT' => '20',
                                        'SORT_BY1' => 'ACTIVE_FROM',
                                        'SORT_ORDER1' => 'DESC',
                                        'SORT_BY2' => 'SORT',
                                        'SORT_ORDER2' => 'ASC',
                                        'FILTER_NAME' => '',
                                        'FIELD_CODE' => array('SLIDER_LINK'),
                                        'PROPERTY_CODE' => array('SLIDER_LINK'),
                                        'CHECK_DATES' => 'Y',
                                        'DETAIL_URL' => '',
                                        'PREVIEW_TRUNCATE_LEN' => '',
                                        'ACTIVE_DATE_FORMAT' => '',
                                        'SET_TITLE' => 'N',
                                        'SET_STATUS_404' => 'N',
                                        'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
                                        'ADD_SECTIONS_CHAIN' => 'N',
                                        'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
                                        'PARENT_SECTION' => '',
                                        'PARENT_SECTION_CODE' => '',
                                        'CACHE_TYPE' => 'A',
                                        'CACHE_TIME' => '36000000',
                                        'CACHE_NOTES' => '',
                                        'CACHE_FILTER' => 'N',
                                        'CACHE_GROUPS' => 'N',
                                        'DISPLAY_TOP_PAGER' => 'N',
                                        'DISPLAY_BOTTOM_PAGER' => 'N',
                                        'PAGER_TITLE' => 'Слайдер',
                                        'PAGER_SHOW_ALWAYS' => 'N',
                                        'PAGER_TEMPLATE' => '',
                                        'PAGER_DESC_NUMBERING' => 'N',
                                        'PAGER_DESC_NUMBERING_CACHE_TIME' => '36000',
                                        'PAGER_SHOW_ALL' => 'N',
                                        'AJAX_OPTION_JUMP' => 'N',
                                        'AJAX_OPTION_STYLE' => 'Y',
                                        'AJAX_OPTION_HISTORY' => 'N',
                                        'AJAX_OPTION_ADDITIONAL' => '',
                                    )
                                ); ?>
                            </div>
                        </div>
                         */ ?>

                        <div class="footer-main row">
                            <div class="col-3 footer-main__column">
                                <h3 class="footer-nav__title">
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:main.include',
                                        '.default',
                                        array(
                                            'PATH' => '/include/footer-col-title-1.php',
                                            'COMPONENT_TEMPLATE' => '.default',
                                            'AREA_FILE_SHOW' => 'file',
                                            'EDIT_TEMPLATE' => '',
                                        ),
                                        false
                                    ); ?>
                                </h3>
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:menu',
                                    'footer_menu_1',
                                    Array(
                                        'ROOT_MENU_TYPE' => 'bottom',
                                        'MAX_LEVEL' => '1',
                                        'CHILD_MENU_TYPE' => 'bottom',
                                        'USE_EXT' => 'Y',
                                        'DELAY' => 'N',
                                        'ALLOW_MULTI_SELECT' => 'Y',
                                        'MENU_CACHE_TYPE' => 'N',
                                        'MENU_CACHE_TIME' => '3600',
                                        'MENU_CACHE_USE_GROUPS' => 'Y',
                                        'MENU_CACHE_GET_VARS' => '',
                                    )
                                ); ?>
                            </div>
                            <div class="col-3 footer-main__column">
                                <h3 class="footer-nav__title">
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:main.include',
                                        '.default',
                                        array(
                                            'PATH' => '/include/footer-col-title-2.php',
                                            'COMPONENT_TEMPLATE' => '.default',
                                            'AREA_FILE_SHOW' => 'file',
                                            'EDIT_TEMPLATE' => '',
                                        ),
                                        false
                                    ); ?>
                                </h3>
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:menu',
                                    'footer_menu_2',
                                    Array(
                                        'ROOT_MENU_TYPE' => 'bottom2',
                                        'MAX_LEVEL' => '1',
                                        'CHILD_MENU_TYPE' => 'bottom2',
                                        'USE_EXT' => 'Y',
                                        'DELAY' => 'N',
                                        'ALLOW_MULTI_SELECT' => 'Y',
                                        'MENU_CACHE_TYPE' => 'N',
                                        'MENU_CACHE_TIME' => '3600',
                                        'MENU_CACHE_USE_GROUPS' => 'Y',
                                        'MENU_CACHE_GET_VARS' => '',
                                    )
                                ); ?>
                            </div>
                            <div class="col-3 footer-main__column">
                                <h3 class="footer-nav__title">
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:main.include',
                                        '.default',
                                        array(
                                            'PATH' => '/include/footer-col-title-3.php',
                                            'COMPONENT_TEMPLATE' => '.default',
                                            'AREA_FILE_SHOW' => 'file',
                                            'EDIT_TEMPLATE' => '',
                                        ),
                                        false
                                    ); ?>
                                </h3>
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:menu',
                                    'footer_menu_3',
                                    Array(
                                        'ROOT_MENU_TYPE' => 'bottom3',
                                        'MAX_LEVEL' => '1',
                                        'CHILD_MENU_TYPE' => 'bottom3',
                                        'USE_EXT' => 'Y',
                                        'DELAY' => 'N',
                                        'ALLOW_MULTI_SELECT' => 'Y',
                                        'MENU_CACHE_TYPE' => 'N',
                                        'MENU_CACHE_TIME' => '3600',
                                        'MENU_CACHE_USE_GROUPS' => 'Y',
                                        'MENU_CACHE_GET_VARS' => '',
                                    )
                                ); ?>
                            </div>
                            <div class="col-3 footer-main__column">
                                <h3 class="footer-nav__title">
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:main.include',
                                        '.default',
                                        array(
                                            'PATH' => '/include/footer-col-title-4.php',
                                            'COMPONENT_TEMPLATE' => '.default',
                                            'AREA_FILE_SHOW' => 'file',
                                            'EDIT_TEMPLATE' => '',
                                        ),
                                        false
                                    ); ?>
                                </h3>
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:menu',
                                    'footer_menu_4',
                                    Array(
                                        'ROOT_MENU_TYPE' => 'bottom4',
                                        'MAX_LEVEL' => '1',
                                        'CHILD_MENU_TYPE' => 'bottom4',
                                        'USE_EXT' => 'Y',
                                        'DELAY' => 'N',
                                        'ALLOW_MULTI_SELECT' => 'Y',
                                        'MENU_CACHE_TYPE' => 'N',
                                        'MENU_CACHE_TIME' => '3600',
                                        'MENU_CACHE_USE_GROUPS' => 'Y',
                                        'MENU_CACHE_GET_VARS' => '',
                                    )
                                ); ?>
                            </div>
                        </div>
                        <div class="footer-bottom">
                            <p class="footer-bottom__copyright">© Manom.ru 2018-<?=date('Y')?></p>

                            <div class="footer-bottom__number">

                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:main.include',
                                    '.default',
                                    [
                                        'PATH' => '/include/phone.php',
                                        'COMPONENT_TEMPLATE' => '.default',
                                        'AREA_FILE_SHOW' => 'file',
                                        'EDIT_TEMPLATE' => '',
                                    ],
                                    false
                                ); ?>
                            </div>
                            <?php $APPLICATION->IncludeComponent(
                                'bitrix:main.include',
                                '.default',
                                [
                                    'PATH' => '/include/time.php',
                                    'COMPONENT_TEMPLATE' => '.default',
                                    'AREA_FILE_SHOW' => 'file',
                                    'EDIT_TEMPLATE' => '',
                                ],
                                false
                            ); ?>
                            <a href="/public_offer_agreement/" class="footer-bottom__contract">
                                Договор публичной оферты
                            </a>
                            <a href="/privacy_policy/" class="footer-bottom__policy">Политика конфиденциальности</a>
                        </div>
                    </div>
                </footer>
            <?php endif; ?>
        </div>

        <?php
        //$GLOBALS["MY_DEBUG"] = $arResult; (Пример принта массива компанента ($arResult))
        function print_my_debug()
        {
            if (empty($GLOBALS["MY_DEBUG"])) {
                return "";
            }
            echo "<pre class='dnd-dump' style='font-size:14px;'>";
            function wrap($color, $text)
            {
                return "<span style='color:".$color."'>[".$text."]</span>";
            }

            function filter_tilda_keys(&$a)
            {
                static $level = 0;
                $tab = "    ";
                $len = 0;
                foreach ($a as $k => $v) {
                    if (substr($k, 0, 1) != "~") {
                        if (is_array($v)) {
                            echo str_repeat($tab, $level).wrap("#ffffff", $k)."\n";
                            if (!empty($v)) {
                                $level++;
                                filter_tilda_keys($v);
                            }
                        } elseif (is_string($v)) {
                            echo str_repeat($tab, $level).wrap("#5050ff", $k)." = ".(strlen($v) < 40 ? $v : substr(
                                        $v,
                                        0,
                                        40
                                    )."…")."\n";
                        } else {
                            echo str_repeat($tab, $level).wrap("green", $k)." = ".$v."\n";
                        }
                        //if($len++ > 2 && $level != 0) {echo str_repeat($tab, $level).wrap("red", "N(".count($a).")")."\n";break;}
                    }
                }
                $level--;
                echo "";
            }

            filter_tilda_keys($GLOBALS["MY_DEBUG"]);
            //print_r($GLOBALS["MY_DEBUG"]);
            echo "</pre>";
        }
        print_my_debug();
        ?>

        <script>
          grecaptcha.ready(function()
          {
            grecaptcha.execute('6LeuEIEUAAAAAFd1nHH6PD8ckNxVwX6p0_6j_Hxr', {action: 'action_name'});
    //             .then(function(token) {});
          });
        </script>

        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/scripts.min.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/coffee/pushUpJS/pushUp.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/jquery.maskedinput.min.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/jquery.suggestions.min.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/coffee/main.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/responsive.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/coffee/vue-main.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/bootstrap-datepicker.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/locales/bootstrap-datepicker.ru.min.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/ui-accord.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/scroll.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/anchor.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/limit-img.js"></script>
        <!-- <script src="https://unpkg.com/swiper/js/swiper.min.js"></script> -->
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/swiper.min.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/assets/js/swiper-photo.js"></script>

        <script>
          //Маска для ввода телефона
          $(function()
          {
            $('#sci-login__tel').mask('+7 (999) 999-99-99');
            $('#sci-login__tel_alt').mask('+7 (999) 999-99-99');
            $('#sci-contact__tel').mask('+7 (999) 999-99-99');
            $('#sci-contact__ur-phone').mask('+7 (999) 999-99-99');

              window.gtmActions.setCurrency("<?=\Manom\GTM::getCurrency()?>");
              window.gtmActions.setProducts(<?=\Manom\GTM::getProductsOnPageJS()?>);
          });
        </script>

        <meta name="yandex-verification" content="5d0e6370947cc2e9"/>
        <div id="event_push_up"></div>

        <!-- <script type="text/javascript">
            $(document).ready(function(){
                $.fn.setPushUp("Ошибка", "Можно вводить только латинские и кириличисткие символы",false,"message",false,10000);
            });
        </script> -->
    </body>
</html>