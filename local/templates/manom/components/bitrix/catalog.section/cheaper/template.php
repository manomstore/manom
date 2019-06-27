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
<div class="tab-content product-discount__block row">
	<?foreach ($arResult['ITEMS'] as $key => $value) {?>
		<?$arCanBuy = CCatalogSKU::IsExistOffers($value['ID'], $arResult['IBLOCK_ID']);?>
		<div class="product-discount__column col-3">
			<div class="preview-prod1">
				<img src="<?=$value['PREVIEW_PICTURE']['SRC'] ? $value['PREVIEW_PICTURE']['SRC'] : $value['DETAIL_PHOTO']['SRC'];?>" alt="">
				<div class="preview-prod1__descr">
					<h3 class="preview-prod1__name">
						<a href="<?=getLinkForOffer($value)?>"><?=$value['NAME']?></a>
					</h3>
				</div>
				<?if($value['PROPERTIES']['BS_STR']['VALUE']){?>
					<div class="preview-prod1__text">Недостатки:
						<?foreach ($value['PROPERTIES']['BS_STR']['VALUE'] as $i => $str) {?>
							<p class="preview-prod1__text_item"><?=$str?></p>
						<?}?>
					</div>
				<?}?>
				<div class="preview-prod1__bottom_left">
					<?
					$firstOffer = $value['OFFERS'][0];
					$arPrice = CCatalogProduct::GetOptimalPrice($value['ID'], 1, $USER->GetUserGroupArray());
					?>
					<p class="preview-prod1__bottom_price"><?=$arPrice['RESULT_PRICE']['DISCOUNT_PRICE']?><span> ₽</span>	</p>
					<?if($arPrice['RESULT_PRICE']['BASE_PRICE'] != $arPrice['RESULT_PRICE']['DISCOUNT_PRICE']){?>
						<p class="preview-prod1__bottom_old-price"><?=$arPrice['RESULT_PRICE']['BASE_PRICE']?></p>
					<?}?>
				</div>
				<div class="p-nav-bottom__shopcart <?=$value['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'addToCartBtn' : ''?>" data-id='<?=$arPrice['PRODUCT_ID']?>' <?=$value['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>></div>
			</div>
		</div>
	<?}?>
</div>
<?endif;?>
