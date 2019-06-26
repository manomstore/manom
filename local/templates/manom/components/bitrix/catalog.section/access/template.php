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
<div class="tab-content product-discount__block">
	<?foreach ($arResult['ITEMS'] as $key => $value) {?>
		<?$arCanBuy = CCatalogSKU::IsExistOffers($value['ID'], $arResult['IBLOCK_ID']);?>
		<div class="product-discount__column">
			<div class="preview-prod1 discount">
				<img src="<?=$value['PROPERTIES']['MORE_PHOTO']['VALUE'] ? CFile::GetPath($value['PROPERTIES']['MORE_PHOTO']['VALUE'][0]) : '';?>" alt="">
				<div class="preview-prod1__descr">
					<h3 class="preview-prod1__name">
						<a href="<?=$value['DETAIL_PAGE_URL']?>"><?=$value['NAME']?></a>
					</h3>
				</div>
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
				<div class="preview-prod1__bottom_left">
					<?
                        $firstOffer = $value['OFFERS'][0];
                        $arPrice = CCatalogProduct::GetOptimalPrice($value['ID'], 1, $USER->GetUserGroupArray());
                        $price = CCatalogProduct::GetOptimalPrice($value['OFFERS'][0]['ID'], 1, $USER->GetUserGroupArray(), 'N');
					?>
					<p class="preview-prod1__bottom_price"><?=$price['RESULT_PRICE']['DISCOUNT_PRICE']?><span> ₽</span>	</p>
					<?if($arPrice['RESULT_PRICE']['BASE_PRICE'] != $price['RESULT_PRICE']['DISCOUNT_PRICE']){?>
						<p class="preview-prod1__bottom_old-price"><?=$price['RESULT_PRICE']['BASE_PRICE']?></p>
					<?}?>
				</div>
				<div class="p-nav-bottom__shopcart <?=$value['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'addToCartBtn' : ''?>" data-id='<?=$arPrice['PRODUCT_ID']?>' <?=$value['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>></div>
			</div>
		</div>

	<?}?>
</div>
<?endif;?>
