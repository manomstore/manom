<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

if (CModule::IncludeModule("sale"))
	$arFilter = Array(
		"USER_ID" => $USER->GetID(),
	);
$db_sales = CSaleOrder::GetList(array(), $arFilter);
while ($ar_sales = $db_sales->Fetch())
{
	echo '<pre>'; print_r($ar_sales); echo '</pre>';
}
?>
