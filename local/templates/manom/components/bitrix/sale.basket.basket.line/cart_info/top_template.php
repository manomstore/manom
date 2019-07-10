<? // if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
//	die();
//}
///**
// * @global array  $arParams
// * @global CUser  $USER
// * @global CMain  $APPLICATION
// * @global string $cartId
// */
//?>
<div style="
  position: fixed;
  width: 100%;
  height: 100%;
  background-color: black;
  left: 0;
  top: 0;
  z-index: 100;
  overflow-y: scroll;
<?= $_REQUEST['show_ci'] ? '' : 'display: none;'; ?>
  ">
  <pre><? print_r($arResult); ?></pre>
</div>

<!-- На этапе корзины этот блок не нужен -->
<div id="cart_info_block" class="shopcart-sidebar__prods">
	<? if ($_REQUEST['AJAX_CART_INFO'] == 'Y'): ?>
		<? $APPLICATION->RestartBuffer(); ?>
	<? endif; ?>
	<? if ($arResult['NUM_PRODUCTS'] > 0): ?>
		<? foreach ($arResult['CATEGORIES'] as $key => $cat) {
			foreach ($cat as $i => $item) { ?>
        <!-- Если товара нет в налиции необходимо добавить класс shopcart-sidebar__prod--stop -->
        <div class="shopcart-sidebar__prod">
          <div class="sci-product__wrapper">
            <div class="sci-product__picture">
              <img src="<?= $item['PIC'] ?>" alt="">
            </div>
            <div class="sci-product__info">
              <div class="sci-product__sum-price">
                <span>
                  <?= str_replace('руб.', '', $item['SUM']) ?> ₽
                </span>
                <!--Добавлять кнопку, если товара нет в наличии-->
                <button
                  class="sci-product__delete sci-top__remove"
                  type="button"
                  aria-label="Удалить товар"
                  data-id="<?= $item['ID'] ?>"
                >
                </button>
              </div>

              <h3 class="sci-product__name">
								<?= $item['NAME'] ?>
              </h3>
            </div>
          </div>
          <p class="shopcart-sidebar__error">Товар закончился, удалите его и оформите заказ</p>
        </div>
				<?
			}
		} ?>
	<? endif; ?>
  <div class="hidden" style="display:none;">
		<?
		global $cartPrice;
		$cartPrice = str_replace('руб.', '', $arResult['TOTAL_PRICE']);
		?>
    <span id="cart_count_prod"><?= $arResult['NUM_PRODUCTS'] ?></span>
    <span id="cart_sum_prod"><?= str_replace('руб.', '', $arResult['TOTAL_PRICE']) ?></span>
  </div>
	<? if ($_REQUEST['AJAX_CART_INFO'] == 'Y'): ?>
		<? die(); ?>
	<? endif; ?>
</div>
