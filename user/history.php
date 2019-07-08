<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("История покупок");
?>
<div class="content">
	<?if ($USER->IsAuthorized()){?>
	<div class="container">
			<section class="personal-burger" style="margin-top: 20px;">
					<div class="container">
							<input class="filter-burger__checkbox" type="checkbox" id="filter-burger">
							<label class="filter-burger" for="filter-burger" title="Фильтр"></label>
					</div>
			</section>
			<section id="pb-history" class="personal-block__section">
					<?$APPLICATION->IncludeComponent("bitrix:sale.personal.order.list", "table", Array(
		"STATUS_COLOR_N" => "green",
			"STATUS_COLOR_P" => "yellow",
			"STATUS_COLOR_F" => "gray",
			"STATUS_COLOR_PSEUDO_CANCELLED" => "red",
			"PATH_TO_DETAIL" => "order_detail.php?ID=#ID#",	// Страница c подробной информацией о заказе
			"PATH_TO_COPY" => "basket.php",	// Страница повторения заказа
			"PATH_TO_CANCEL" => "order_cancel.php?ID=#ID#",	// Страница отмены заказа
			"PATH_TO_BASKET" => "/cart/",	// Страница корзины
			"PATH_TO_PAYMENT" => "payment.php",	// Страница подключения платежной системы
			"ORDERS_PER_PAGE" => "20",	// Количество заказов, выводимых на страницу
			"ID" => $ID,	// Идентификатор заказа
			"SET_TITLE" => "Y",	// Устанавливать заголовок страницы
			"SAVE_IN_SESSION" => "Y",	// Сохранять установки фильтра в сессии пользователя
			"NAV_TEMPLATE" => "",	// Имя шаблона для постраничной навигации
			"CACHE_TYPE" => "A",	// Тип кеширования
			"CACHE_TIME" => "3600",	// Время кеширования (сек.)
			"CACHE_GROUPS" => "Y",	// Учитывать права доступа
			"HISTORIC_STATUSES" => array(	// Перенести в историю заказы в статусах
				0 => "F",
			),
			"ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
			"COMPONENT_TEMPLATE" => "list",
			"PATH_TO_CATALOG" => "/catalog/",	// Путь к каталогу
			"RESTRICT_CHANGE_PAYSYSTEM" => array(	// Запретить смену платежной системы у заказов в статусах
				0 => "0",
			),
			"REFRESH_PRICES" => "N",	// Пересчитывать заказ после смены платежной системы
			"DEFAULT_SORT" => "STATUS",	// Сортировка заказов
			"STATUS_COLOR_DS" => "gray"
		),
		false
	);?>
			</section>
	</div>
	<?}else{
			LocalRedirect("/");
	}?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>