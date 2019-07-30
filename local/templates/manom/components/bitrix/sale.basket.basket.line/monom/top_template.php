<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}
/**
 * @global array  $arParams
 * @global CUser  $USER
 * @global CMain  $APPLICATION
 * @global string $cartId
 */
?>
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
<? if ($_REQUEST['AJAX_MIN_CART'] == 'Y'): ?>
	<? $APPLICATION->RestartBuffer(); ?>
<? endif; ?>

<!-- Если в корзине что-то есть добавлчется класс top-personal__cart--full -->
<a href="/cart/" class=" top-personal__link top-personal__cart top-personal__cart--full" id="mini_cart_header_counter">
  <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/icons/cart.svg" alt="Иконка корзины" width="20" height="16">
	<? if ($prodCount = 0): ?>
    Корзина пуста
	<? else: ?>
		<?= str_replace('руб.', '', $arResult['TOTAL_PRICE']) ?>
    &#8381;
    (<span class="top-count top-personal__count">
      <?= $arResult['NUM_PRODUCTS'] ?>
    </span>)
	<? endif; ?>
</a>

<? $prodCount = 0; ?>
<div class="personal-preview preview-shopcart<?= $arResult['NUM_PRODUCTS'] === 0 ? ' preview-shopcart--empty' : ''; ?>" id="mini_cart_header">
  <div class="personal-preview__wrapper">
    <div class="personal-preview__top">
      <h3 class="personal-preview__title">Корзина</h3>
      <button class="personal-preview__link" type="button">Очистить</button>
    </div>
		<? if ($arResult['NUM_PRODUCTS'] > 0): ?>
			<? foreach ($arResult['CATEGORIES'] as $key => $cat) {
				foreach ($cat as $i => $item) { ?>
					<?
					$prodCount++;
					if ($prodCount > 5) {
						continue;
					} ?>
          <div class="preview-prod" data-cart-item="<?= $item['ID'] ?>">
            <div class="preview-prod__picture">
              <img src="<?= $item['PIC'] ?>" alt="">
            </div>
            <div class="preview-prod__descr">
              <div class="preview-prod-bottom">
                <div class="preview-prod-bottom__price">
									<?= str_replace('руб.', '', $item['SUM']) ?><span> ₽</span>
                </div>
                <button class="preview-prod-bottom__del" type="button" aria-label="Удалить товар" data-cart-item="<?= $item['ID'] ?>"></button>
<!--                <label>-->
<!--                  <input class="preview-prod-bottom__checkbox" type="checkbox" checked>-->
<!--                  <span class="preview-prod-bottom__button preview-prod-bottom__button-cart" data-cart-item="--><?//= $item['ID'] ?><!--"></span>-->
<!--                </label>-->
              </div>
              <h3 class="preview-prod__name">
                <a href="<?= $item['DETAIL_PAGE_URL'] ?>"><?= $item['NAME'] ?> (<?= $item['QUANTITY'] ?>шт.)</a>
              </h3>
            </div>
          </div>
				<?
				}
			} ?>
			<? if ($prodCount > 5): ?>
        <p style="text-align: left;padding: 5px 10px;">Товаров: <?= $prodCount; ?></p>
			<? endif; ?>
      <div class="preview-bottom">
<!--        <div class="preview-bottom__price">-->
<!--					--><?//= str_replace('руб.', '', $arResult['TOTAL_PRICE']) ?><!--<span> ₽</span>-->
<!--        </div>-->
        <a href="/cart/" class="preview-bottom__button">В корзину</a>
      </div>
		<? endif; ?>
  </div>
</div>
<? if ($_REQUEST['AJAX_MIN_CART'] == 'Y'): ?>
	<? die(); ?>
<? endif; ?>
