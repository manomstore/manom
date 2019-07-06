<?// if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
//	die();
//}
///**
// * @global array  $arParams
// * @global CUser  $USER
// * @global CMain  $APPLICATION
// * @global string $cartId
// */
//?>
<!--<div style="-->
<!--  position: fixed;-->
<!--  width: 100%;-->
<!--  height: 100%;-->
<!--  background-color: black;-->
<!--  left: 0;-->
<!--  top: 0;-->
<!--  z-index: 100;-->
<!--  overflow-y: scroll;-->
<?//= $_REQUEST['show_ci'] ? '' : 'display: none;'; ?>
<!--  ">-->
<!--  <pre>--><?// print_r($arResult); ?><!--</pre>-->
<!--</div>-->
<!--<div id="cart_info_block">-->
<!--	--><?// if ($_REQUEST['AJAX_CART_INFO'] == 'Y'): ?>
<!--		--><?// $APPLICATION->RestartBuffer(); ?>
<!--	--><?// endif; ?>
<!--	--><?// if ($arResult['NUM_PRODUCTS'] > 0): ?>
<!--		--><?// foreach ($arResult['CATEGORIES'] as $key => $cat) {
//			foreach ($cat as $i => $item) { ?>
<!--        <div class="shopcart-sidebar__prod">-->
<!--          <img src="--><?//= $item['PIC'] ?><!--" alt="">-->
<!--          <div class="shopcart-sidebar__descr">-->
<!--            <h3 class="shopcart-sidebar__name">-->
<!--							--><?//= $item['NAME'] ?>
<!--            </h3>-->
<!--            <p class="shopcart-sidebar__price">-->
<!--							--><?//= str_replace('руб.', '', $item['SUM']) ?><!--<span> ₽</span>-->
<!--            </p>-->
<!--          </div>-->
<!--        </div>-->
<!--			--><?//
//			}
//		} ?>
<!--	--><?// endif; ?>
<!--  <div class="hidden" style="display:none;">-->
<!--		--><?//
//		global $cartPrice;
//		$cartPrice = str_replace('руб.', '', $arResult['TOTAL_PRICE']);
//		?>
<!--    <span id="cart_count_prod">--><?//= $arResult['NUM_PRODUCTS'] ?><!--</span>-->
<!--    <span id="cart_sum_prod">--><?//= str_replace('руб.', '', $arResult['TOTAL_PRICE']) ?><!--</span>-->
<!--  </div>-->
<!--	--><?// if ($_REQUEST['AJAX_CART_INFO'] == 'Y'): ?>
<!--		--><?// die(); ?>
<!--	--><?// endif; ?>
<!--</div>-->
