<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 */

$this->setFrameMode(true);
// echo "<pre style='text-align:left;'>";print_r($arResult['REVIEW']);echo "</pre>";
?>
<?if ($arResult['ITEMS']):?>
<!-- Дополнительное предложение -->
<section class="additionally">
	<div class="container">
		<h2 class="additionally-h2">Вас может заинтересовать</h2>
		<div class="additionally__block row">
		<?foreach ($arResult['ITEMS'] as $key => $value) {?>
			<?$arCanBuy = CCatalogSKU::IsExistOffers($value['ID'], $arResult['IBLOCK_ID']);?>
			<div class="col-3">
				<div class="product-card border">
					<div class="product-card__img">
						<?foreach ($value['PROPERTIES']['MORE_PHOTO']['VALUE'] as $p => $photo) {?>
							<div class="product-card__slide">
								<img src="<?=CFile::GetPath($photo);?>" alt="">
							</div>
						<?}?>
					</div>
					<p class="p-label-top active">
							<?if ($value['PROPERTIES']['PRODUCT_OF_THE_DAY']['VALUE'] == 'Да') {?>
							Товар дня
							<?}?>
						</p>
					<div class="p-nav-top">
						<label>
							<input class="p-nav-top__checkbox" type="checkbox">
							<div class="p-nav-top__favorite" title="в избранное"></div>
						</label>
						<div class="p-nav-top__list"></div>
					</div>
					<div class="p-nav-middle">
						<?if ($value['PROPERTIES']['SELL_PROD']['VALUE'] == 'Да') {?>
						<div class="p-nav-middle__sale active">Распродажа</div>
						<?}?>

						<div class="p-nav-middle__rating">
							<?for ($i=0; $i < 5; $i++) {
								if ($i >= $arResult['REVIEW'][$value['ID']]['rating']) {
									?> <span> ★ </span> <?
								}else{
									?> ★ <?
								}
							}?></div>
						<div class="p-nav-middle__comments"><span><?=$arResult['REVIEW'][$value['ID']]['count']?></span></div>
					</div>
					<h3 class="p-name">
						<a href="<?=$value['DETAIL_PAGE_URL']?>"><?=$value['NAME']?></a>
					</h3>
					<div class="p-nav-bottom">
						<?
						$arPrice = CCatalogProduct::GetOptimalPrice($value['ID'], 1, $USER->GetUserGroupArray());
						?>
						<div class="p-nav-bottom__price">
							<?=$arPrice['RESULT_PRICE']['DISCOUNT_PRICE']?><span> ₽</span>
							<?if($arPrice['RESULT_PRICE']['BASE_PRICE'] != $arPrice['RESULT_PRICE']['DISCOUNT_PRICE']){?>
								<div class="p-nav-bottom__oldprice"><?=$arPrice['RESULT_PRICE']['BASE_PRICE']?></div>
							<?}?>
						</div>
						<div class="p-nav-bottom__shopcart <?=$value['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'addToCartBtn' : ''?>" data-id='<?=$arPrice['PRODUCT_ID']?>' <?=$value['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>></div>
					</div>
				</div>
			</div>
		<?}?>
		<!-- <div class="col-3">
			<div class="product-card border">
				<div class="product-card__img">
					<div class="product-card__slide">
						<img src="img/4396804.jpg" alt="">
					</div>
					<div class="product-card__slide">
						<img src="img/4396801.jpg" alt="">
					</div>
				</div>
				<p class="p-label-top">Товар дня</p>
				<div class="p-nav-top">
					<label>
						<input class="p-nav-top__checkbox" type="checkbox">
						<div class="p-nav-top__favorite" title="в избранное"></div>
					</label>
					<div class="p-nav-top__list"></div>
				</div>
				<div class="p-nav-middle">
					<div class="p-nav-middle__sale active">Распродажа</div>
					<div class="p-nav-middle__rating">★ ★ ★ <span>★ ★</span></div>
					<div class="p-nav-middle__comments"><span>12</span></div>
				</div>
				<h3 class="p-name">
					Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple
				</h3>
				<div class="p-nav-bottom">
					<div class="p-nav-bottom__price">
						5 000<span> ₽</span>
						<div class="p-nav-bottom__oldprice">40 000</div>
					</div>
					<div class="p-nav-bottom__shopcart"></div>
				</div>
			</div>
		</div> -->
		</div>
	</div>
</section>
<?endif;?>
