<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/**
 * @global array $arParams
 * @global CUser $USER
 * @global CMain $APPLICATION
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
<?=$_REQUEST['show_ci'] ? '' : 'display: none;';?>
">
	<pre><?print_r($arResult);?></pre>
</div>
<?if ($_REQUEST['AJAX_MIN_CART'] == 'Y'):?>
<?$APPLICATION->RestartBuffer();?>
<?endif;?>
<a href="/cart/" class="top-personal__shopcart" id="mini_cart_header_counter">
	<img src="<?=SITE_TEMPLATE_PATH?>/assets/img/top-shopcart.svg" alt="">
	<span class="top-count"><?=$arResult['NUM_PRODUCTS']?></span>
</a>

<?$prodCount = 0;?>
<div class="preview-shopcart<?=$arResult['NUM_PRODUCTS'] === 0 ? ' preview-shopcart--empty' : '';?>" id="mini_cart_header">
	<?if ($arResult['NUM_PRODUCTS'] > 0):?>
		<?foreach ($arResult['CATEGORIES'] as $key => $cat) {
			foreach ($cat as $i => $item) {?>
				<?$prodCount++;
				if($prodCount>5) continue;?>
				<div class="preview-prod" data-cart-item="<?=$item['ID']?>">
					<img src="<?=$item['PIC']?>" alt="">
					<div class="preview-prod__descr">
						<h3 class="preview-prod__name">
							<a href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['NAME']?> (<?=$item['QUANTITY']?>шт.)</a>
						</h3>
						<div class="preview-prod-bottom">
							<div class="preview-prod-bottom__price">
								<?=str_replace('руб.', '', $item['SUM'])?><span> ₽</span>
							</div>
							<label>
								<input class="preview-prod-bottom__checkbox" type="checkbox" checked>
								<span class="preview-prod-bottom__button preview-prod-bottom__button-cart" data-cart-item="<?=$item['ID']?>"></span>
							</label>
						</div>
					</div>
				</div>
			<?}
		}?>
		<div class="hr"></div>
		<?if ($prodCount > 5):?>
			<p style="text-align: left;padding: 5px 10px;">Товаров: <?=$prodCount;?></p>
		<?endif;?>
		<div class="preview-bottom">
			<div class="preview-bottom__price">
				<?=str_replace('руб.', '', $arResult['TOTAL_PRICE'])?><span> ₽</span>
			</div>
			<a href="/cart/" class="preview-bottom__button" style="margin-top: 10px;">В корзину</a>
		</div>
	<?endif;?>
</div>
<?if ($_REQUEST['AJAX_MIN_CART'] == 'Y'):?>
<?die();?>
<?endif;?>
