<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск");
?>
<div class="content">
	<div class="container">
			<?$APPLICATION->IncludeComponent(
		"bitrix:search.page",
		"search_page",
		array(
			"ELEMENT_SORT_FIELD" => "NAME",
			"TAGS_PAGE_ELEMENTS" => "150",
			"TAGS_PERIOD" => "30",
			"TAGS_URL_SEARCH" => "/search/index.php",
			"TAGS_INHERIT" => "Y",
			"FONT_MAX" => "50",
			"FONT_MIN" => "10",
			"COLOR_NEW" => "000000",
			"COLOR_OLD" => "C8C8C8",
			"PERIOD_NEW_TAGS" => "",
			"SHOW_CHAIN" => "Y",
			"COLOR_TYPE" => "Y",
			"WIDTH" => "100%",
			"USE_SUGGEST" => "Y",
			"SHOW_RATING" => "Y",
			"PATH_TO_USER_PROFILE" => "",
			"AJAX_MODE" => "Y",
			"RESTART" => "Y",
			"NO_WORD_LOGIC" => "N",
			"USE_LANGUAGE_GUESS" => "Y",
			"CHECK_DATES" => "Y",
			"USE_TITLE_RANK" => "Y",
			"DEFAULT_SORT" => $_REQUEST['search_sort'] == 1 ? 'date' : 'rank',
			"FILTER_NAME" => "",
			"arrFILTER" => array(
				0 => "iblock_catalog",
			),
			"SHOW_WHERE" => "N",
			"arrWHERE" => "",
			"SHOW_WHEN" => "N",
			"PAGE_RESULT_COUNT" => "50",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"DISPLAY_TOP_PAGER" => "Y",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "Результаты поиска",
			"PAGER_SHOW_ALWAYS" => "Y",
			"PAGER_TEMPLATE" => "catalog_section_alt",
			"AJAX_OPTION_SHADOW" => "Y",
			"AJAX_OPTION_JUMP" => "Y",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "Y",
			"AJAX_OPTION_ADDITIONAL" => "",
			"COMPONENT_TEMPLATE" => "search_page",
			"SHOW_ITEM_TAGS" => "Y",
			"SHOW_ITEM_DATE_CHANGE" => "Y",
			"SHOW_ORDER_BY" => "Y",
			"SHOW_TAGS_CLOUD" => "N",
			"STRUCTURE_FILTER" => "structure",
			"NAME_TEMPLATE" => "",
			"SHOW_LOGIN" => "Y",
			"PATH_TO_SONET_MESSAGES_CHAT" => "/company/personal/messages/chat/#USER_ID#/",
			"arrFILTER_iblock_catalog" => array(
				0 => "7",
			)
		),
		false
	);?>
	</div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
