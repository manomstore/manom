<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Профиль");
CModule::IncludeModule('sale');

$ret = getAllProdsWithoutReviewFromOrders();

?>
<pre><?print_r($ret);?></pre>
