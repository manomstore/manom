<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?>
<div class="content">
	<div class="container">
			<?$APPLICATION->IncludeComponent("bitrix:main.register", "reg", Array(
		"AUTH" => "Y",	// Автоматически авторизовать пользователей
			"REQUIRED_FIELDS" => array(	// Поля, обязательные для заполнения
				0 => "EMAIL",
				1 => "PERSONAL_PHONE",
			),
			"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
			"SHOW_FIELDS" => array(	// Поля, которые показывать в форме
				0 => "EMAIL",
				1 => "NAME",
				2 => "LAST_NAME",
				3 => "PERSONAL_GENDER",
				4 => "PERSONAL_BIRTHDAY",
				5 => "PERSONAL_PHONE",
				6 => "PERSONAL_MOBILE",
				7 => "PERSONAL_STREET",
				8 => "PERSONAL_MAILBOX",
				9 => "PERSONAL_CITY",
				10 => "PERSONAL_STATE",
				11 => "PERSONAL_ZIP",
				12 => "PERSONAL_COUNTRY",
			),
			"SUCCESS_PAGE" => "/auth/confirmation.php",	// Страница окончания регистрации
			"USER_PROPERTY" => "",	// Показывать доп. свойства
			"USER_PROPERTY_NAME" => "",
			"USE_BACKURL" => "Y",	// Отправлять пользователя по обратной ссылке, если она есть
			"COMPONENT_TEMPLATE" => "registration"
		),
		false
	);?>
        <script>
            $(function () {
                window.gtmActions.initCommonData(<?=GTM::getDataJS("other")?>);
            });
        </script>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>