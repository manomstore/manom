<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>
<pre style="text-align: left;"><? //print_r($arResult)?></pre>
<div class="catalog-main catalog-main-sr">
	<?

	use Bitrix\Main\Application,
		Bitrix\Main\Web\Uri;

	$request = Application::getInstance()->getContext()->getRequest();
	$uriString = $request->getRequestUri();
	$uri = new Uri($uriString);
	$uri->addParams(["search_sort" => "1"]);
	$urlSort1 = $uri->getUri();
	$uri->addParams(["search_sort" => "2"]);
	$urlSort2 = $uri->getUri();
	?>

	<? global $USER; ?>
	<? if ($arResult['SEARCH']) { ?>
		<aside class="catalog-filter">
			<ul class="catalog-filter__ul">
				<li class="catalog-filter__li">
					<input type="checkbox" class="checkbox-1" checked="">
					<i></i>
					<h3>Сортировка</h3>
					<p><a href="<?= $urlSort1 ?>">По дате</a></p>
					<p><a href="<?= $urlSort2 ?>">По релевантности</a></p>
				</li>
			</ul>
		</aside>
	<? } ?>
	<section class="catalog-block ">
		<? if ($arResult['SEARCH']) { ?>
			<div id='PROPDS_BLOCK'>
				<div class="cb-line no-gutters ffdf" style="display: flex;">
					<? foreach ($arResult['SEARCH'] as $key => $arItems) { ?>
						<?
						$arItems['ID'] = $arItems['ITEM_ID'];
						$arCanBuy = CCatalogSKU::IsExistOffers($arItems['ID'], 7); ?>
						<?
						$arPrice = CCatalogProduct::GetOptimalPrice($arItems['ID'], 1, $USER->GetUserGroupArray(), 'N');
						$arItems['CATALOG_QUANTITY'] = $quantity = $nearestQuantity = CCatalogProduct::GetNearestQuantityPrice(
							$arItems['ID'],
							1,
							$USER->GetUserGroupArray()
						);
						if (!$arPrice || count($arPrice) <= 0) {
							if ($nearestQuantity) {
								$quantity = $nearestQuantity;
								$arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
							}
						}
						$morePhoto = [];
						$sellProd = false;
						$getMorePhoto = CIBlockElement::GetProperty(7, $arItems['ID'], "sort", "asc", ["CODE" => "MORE_PHOTO"]);
						while ($resMorePhoto = $getMorePhoto->Fetch()) {
							$morePhoto[] = $resMorePhoto['VALUE'];
						}
						$getProp = CIBlockElement::GetProperty(7, $arItems['ID'], "sort", "asc", ["CODE" => "SELL_PROD"]);
						if ($resProp = $getProp->Fetch()) {
							$sellProd = $resProp['VALUE'];
						}
						?>
						<div class="cb-line__item">
							<div class="product-card cb-line-card <?= $arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable' ?>">  <!-- disable -->
								<div class="product-card__img cb-line-card__img">
									<? foreach ($morePhoto as $val => $img) { ?>
										<? if ($morePhoto) {
											$file = CFile::ResizeImageGet($img, ['width' => 170, 'height' => 170], BX_RESIZE_IMAGE_PROPORTIONAL, true);
										} ?>
										<div class="product-card__slide">
											<img src="<?= $file['src'] ?>" alt="">
										</div>
									<? } ?>
								</div>
								<div class="cb-line-card__main">
									<div class="p-nav-middle">
										<? if ($sellProd) { ?>
											<div class="p-nav-middle__sale active">
												<?= $sellProd ?>
											</div>
										<? } ?>
<!--										<div class="p-nav-middle__rating cb-line-card__rating">-->
<!--											--><?// for ($i = 0; $i < 5; $i++) {
//												if ($i >= $arResult['REVIEW'][$arItems['ID']]['rating']) {
//													?><!-- <span> ★ </span> --><?//
//												} else {
//													?><!-- ★ --><?//
//												}
//											} ?><!--</div>-->
<!--										<div class="p-nav-middle__comments"><span>--><?//= $arResult['REVIEW'][$arItems['ID']]['count'] ?><!--</span></div>-->
									</div>
									<div class="cb-line-card__text">
										<h3 class="p-name cb-line-name">
											<a href="<?= $arItems['URL'] ?>"><?= $arItems['TITLE'] ?></a>
										</h3>
										<div class="p-cart-properties cb-line-properties">
											<? foreach ($arItems['DISPLAY_PROPERTIES'] as $g => $arProp) { ?>
												<? if (!is_array($arProp['VALUE'])) { ?>
													<p>
														<span class="p-cart-properties__name"><?= $arProp['NAME'] ?></span>
														<span class="p-cart-properties__value"><?= $arProp['VALUE'] ?></span>
													</p>
												<? } ?>
											<? } ?>
										</div>
									</div>
									<div class="cb-line-nav-top">
										<? if ($arItems['CATALOG_QUANTITY'] < 1 and $arCanBuy == 0) { ?>
											<div class="cb-line-nav-top__available">
												<?= $arResult['ORIGINAL_PARAMETERS']['MESS_NOT_AVAILABLE'] ?>
											</div>
										<? } ?>
										<label>
											<input class="cb-line-nav-top__checkbox" type="checkbox" <?= checkProdInFavoriteAndCompareList(
												$arPrice['PRODUCT_ID'],
												'UF_FAVORITE_ID'
											) ? 'checked' : ''; ?>>
											<div class="addToFavoriteListOnFP cb-line-nav-top__favorite addToFavoriteList <?= !checkProdInFavoriteAndCompareList(
												$arPrice['PRODUCT_ID'],
												'UF_FAVORITE_ID'
											) ? 'notActive' : ''; ?>" data-id='<?= $arPrice['PRODUCT_ID'] ?>'>В&nbsp;избранное
											</div>
										</label>
										<div class="cb-line-nav-top__list addToCompareList <?= !checkProdInFavoriteAndCompareList(
											$arPrice['PRODUCT_ID'],
											'UF_COMPARE_ID'
										) ? 'notActive' : ''; ?>" data-id='<?= $arPrice['PRODUCT_ID'] ?>'>Сравнить
										</div>
										<!-- <label>
														<input class="cb-line-nav-top__checkbox" type="checkbox" checked>
														<div class="cb-line-nav-top__favorite">В&nbsp;избранное</div>
										</label>
										<div class="cb-line-nav-top__list">Сравнить</div> -->
									</div>
								</div>
								<div class="p-nav-bottom cb-line-bottom">
									<div class="p-nav-bottom">
										<? if (!$arItems['OFFERS']) { ?>
											<? if ($arPrice['RESULT_PRICE']['BASE_PRICE'] > $arPrice['RESULT_PRICE']['DISCOUNT_PRICE']) { ?>
												<div class="p-nav-bottom__price">
													<?= $arPrice['RESULT_PRICE']['DISCOUNT_PRICE']; ?>
													<span> ₽</span>
													<div class="p-nav-bottom__oldprice"><?= $arPrice['RESULT_PRICE']['BASE_PRICE'] ?></div>
												</div>
											<? } else { ?>
												<div class="p-nav-bottom__price">
													<?= $arPrice['RESULT_PRICE']['BASE_PRICE'] ?>
													<span> ₽</span>
												</div>
											<? } ?>
										<? } ?>
									</div>
									<button class="cb-line-bottom__buy <?= $arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'addToCartBtn' : '' ?>"
									        data-id='<?= $arPrice['PRODUCT_ID'] ?>' <?= $arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable' ?>>Купить
									</button>
								</div>
							</div>
						</div>
					<? } ?>
				</div>
				<?= $arResult["NAV_STRING"] ?>
			</div>
		<? } else { ?>
			<div class="message-incorrect" style="text-align:center;">
				<p>Упс 🙁 По запросу «<?= $_REQUEST['q'] ?>» ничего не нашли</p>
				<br>
				<p>Попробуй изменить формулировку или воспользуйся нашим каталогом</p>
				<? echo GetMessage("CT_BSP_CORRECT_AND_CONTINUE"); ?>
			</div>
		<? } ?>
	</section>

</div>
