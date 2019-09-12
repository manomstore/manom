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
// echo "<pre style='text-align:left;'>";print_r($arResult['ITEMS'][1]);echo "</pre>";
?>
<?if ($arResult['ITEMS']):?>
<div class="tab-content">
	<div class="cb-block">
		<?foreach ($arResult['ITEMS'] as $key => $value) {?>
			<?$arCanBuy = CCatalogSKU::IsExistOffers($value['ID'], $arResult['IBLOCK_ID']);?>
			<div class="cb-block__item col-3">
				<div class="product-card">
					<div class="product-card__img">
						<img src="<?=$value['PROPERTIES']['MORE_PHOTO']['VALUE'] ? CFile::GetPath($value['PROPERTIES']['MORE_PHOTO']['VALUE'][0]) : '';?>" alt="">
					</div>
					<h3 class="p-name">
						<a href="<?=$value['DETAIL_PAGE_URL']?>"><?=$value['NAME']?></a>
					</h3>
					<?if($value['PROPERTIES']['ACESS_STR']['VALUE']){?>
						<div class="preview-prod1__text">
							<?foreach ($value['PROPERTIES']['ACESS_STR']['~VALUE'] as $i => $str) {?>
								<p class="preview-prod1__text_item"><?=$str?></p>
							<?}?>
						</div>
					<?}?>
					<?if($value['PROPERTIES']['BS_STR']['VALUE']){?>
						<div class="preview-prod1__text">
							<?foreach ($value['PROPERTIES']['BS_STR']['~VALUE'] as $i => $str) {?>
								<p class="preview-prod1__text_item"><?=$str?></p>
							<?}?>
						</div>
					<?}?>
					<div class="p-nav-bottom">
						<div class="p-nav-bottom__price">
							<?
								$firstOffer = $value['OFFERS'][0];
								$arPrice = CCatalogProduct::GetOptimalPrice($value['ID'], 1, $USER->GetUserGroupArray());
								$price = CCatalogProduct::GetOptimalPrice($value['OFFERS'][0]['ID'], 1, $USER->GetUserGroupArray(), 'N');
							?>
							<?=$price['RESULT_PRICE']['DISCOUNT_PRICE']?><span> ₽</span>
							<?if($arPrice['RESULT_PRICE']['BASE_PRICE'] != $price['RESULT_PRICE']['DISCOUNT_PRICE']){?>
								<div class="p-nav-bottom__oldprice"><?=$price['RESULT_PRICE']['BASE_PRICE']?></div>
							<?}?>
						</div>
						<div class="p-nav-bottom__shopcart <?=$value['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'addToCartBtn' : ''?>" data-id='<?=$arPrice['PRODUCT_ID']?>' <?=$value['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>></div>
					</div>
				</div>
			</div>

		<?}?>
	</div>
</div>
<?endif;?>
