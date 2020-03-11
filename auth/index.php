<?
if ($_REQUEST["change_password"] === "yes") {
    define("NEED_AUTH", true);
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//if(isset($_REQUEST['backurl']) && strlen($_REQUEST['backurl'])>0)
//    LocalRedirect($backurl);
$APPLICATION->SetTitle("Авторизация");
?>
<div class="content">
    <div class="container">
    <!--    <pre>--><?//print_r($_REQUEST)?><!--</pre>-->
        <?if($_REQUEST['confirm_code'] or $_REQUEST['confirm_registration'] == 'yes') {?>
            <?$APPLICATION->IncludeComponent("bitrix:system.auth.confirmation","manom",Array(
                    "USER_ID" => "confirm_user_id",
                    "CONFIRM_CODE" => "confirm_code",
                    "LOGIN" => "login"
                )
            );?>
        <?}?>
        <?$APPLICATION->IncludeComponent(
        "bitrix:system.auth.form",
        "auth-cart",
        array(
            "REGISTER_URL" => "/auth/registration.php",
            "FORGOT_PASSWORD_URL" => "/auth/foget.php",
            "PROFILE_URL" => "/user/profile.php",
            "SHOW_ERRORS" => "Y",
            "COMPONENT_TEMPLATE" => "auth-cart"
        ),
        false
    );?>
        <!--        <p>Вы зарегистрированны и успешно авторизовались.</p>-->
        <!--        <p>Текст</p>-->
        <!--        <p><a href="--><?//=SITE_DIR?><!--">Вернуться на главную страницу</a></p>-->
        <script>
            $(function () {
                window.gtmActions.initCommonData(<?=GTM::getDataJS("other")?>);
            });
        </script>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
