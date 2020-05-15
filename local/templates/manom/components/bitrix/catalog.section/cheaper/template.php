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
?>
<?if ($arResult['ITEMS']):
echo "<pre style='text-align:left;'>";print_r($arResult['ITEMS'][0]);echo "</pre>";?>
<div class="tab-content row">
	<div class="cb-block">
		<?foreach ($arResult['ITEMS'] as $key => $value) {?>
			<?$arCanBuy = CCatalogSKU::IsExistOffers($value['ID'], $arResult['IBLOCK_ID']);?>
			<div class=" cb-block__item col-3">
				<div class="product-card">
					<div class="product-card__img">
						<img src="<?=$value['PREVIEW_PICTURE']['SRC'] ? $value['PREVIEW_PICTURE']['SRC'] : $value['DETAIL_PHOTO']['SRC'];?>" alt="">
					</div>
					<h3 class="preview-prod1__name">
						<a href="<?=getLinkForOffer($value)?>"><?=$value['NAME']?></a>
					</h3>

					<?if($value['PROPERTIES']['BS_STR']['VALUE']){?>
						<div class="preview-prod1__text">Недостатки:
							<?foreach ($value['PROPERTIES']['BS_STR']['VALUE'] as $i => $str) {?>
								<p class="preview-prod1__text_item"><?=$str?></p>
							<?}?>
						</div>
					<?}?>
					<div class="p-nav-bottom">
						<div class="p-nav-bottom__price">
							<?
							$firstOffer = $value['OFFERS'][0];
							$arPrice = CCatalogProduct::GetOptimalPrice($value['ID'], 1, $USER->GetUserGroupArray());
							?>
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
	</div>
</div>
<?endif;?>
