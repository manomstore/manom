<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */
?>
<pre style="text-align:left;<?if(!$_GET['pr']) echo 'display: none';?>"><?print_r($arResult);?></pre>
<?if ($_REQUEST['AJAX_CART'] == 'Y'):?>
	<?$APPLICATION->RestartBuffer();?>
<?endif;?>
<?if(count($arResult['GRID']['ROWS']) <= 0):?>
	<script type="text/javascript">
		location.href = '/cart/basket.php';
	</script>
<?endif?>
<?foreach ($arResult['GRID']['ROWS'] as $key => $value) {?>
	<div class="sci-product w-100" data-id="<?=$value['ID']?>">
		<div class="sci-top">
			<div class="sci-top__prod">
				<img src="<?=$value['PIC']?>" alt="">
				<h3 class="sci-top__name">
					<a href="<?=$value['DETAIL_PAGE_URL']?>"><?=$value['NAME']?></a>
				</h3>
			</div>
			<div class="sci-top__count"><span><?=$value['QUANTITY']?></span> шт.
				<div class="sci-top__count-up" data-id="<?=$value['ID']?>" data-q="<?=$value['QUANTITY']?>"></div>
				<div class="sci-top__count-down" data-id="<?=$value['ID']?>" data-q="<?=$value['QUANTITY']?>"></div>
			</div>
			<div class="sci-top__prices">
				<div class="sci-top__new-price"><span><?=str_replace('руб.', '', $value['SUM'])?></span> ₽</div>
				<!-- <p class="sci-top__old-price">10 000</p> -->
			</div>
			<div class="sci-top__remove" data-id="<?=$value['ID']?>">
				<img src="/bitrix/templates/manom/assets/img/close-black.svg" alt="">
			</div>
		</div>
		<?
		$acessForThisElement = array();
		$dopProdForThisElement = array();
		foreach ($arResult['CML_PROD'] as $t => $el) {
			if ($el['OFFERS'][md5($value['PRODUCT_ID'])]) {
				$acessForThisElement = $el['ACESS_OBJ'];
				$dopProdForThisElement = $el['DOP_SERV_OBJ'];
			}
		}
		?>
		<?if ($acessForThisElement or $dopProdForThisElement):?>
			<div class="sci-add">
				<?if ($acessForThisElement){?>
					<div class="sci-add__products">
						<h3 class="sci-add__title">С этим товаром  покупают</h3>
						<?foreach ($acessForThisElement as $r => $p) {?>
							<div class="sci-add__prod" data-id="<?=$p['id']?>">
								<img src="<?=$p['img']?>" alt="">
								<h3 class="sci-add__name" <? empty($p['img']) ? 'style="margin-left: 50px;"' : '';?>>
									<a href="<?=$p['url']?>"><?=$p['name']?></a>
								</h3>
								<div class="sci-add__prices">
									<div class="sci-add__price"><span><?=number_format($p['price'], 0, '', ' ')?></span> ₽	</div>
								</div>
								<div class="sci-add__button addToCartBtn addToCartBtn_inCart" data-id="<?=$p['id']?>"></div>
							</div>
						<?}?>
					</div>
				<?}?>
				<?if ($dopProdForThisElement){?>
					<div class="sci-add__services">
						<h3 class="sci-add__title">Дополнительные услуги</h3>
						<?foreach ($dopProdForThisElement as $r => $p) {?>
							<div class="sci-add__prod">
								<img src="<?=$p['img']?>" alt="">
								<h3 class="sci-add__name" <? empty($p['img']) ? 'style="margin-left: 50px;"' : '';?>>
									<a href="<?=$p['url']?>"><?=$p['name']?></a>
								</h3>
								<div class="sci-add__prices">
									<div class="sci-add__price"><span><?=number_format($p['price'], 0, '', ' ')?></span> ₽	</div>
								</div>
								<div class="sci-add__button addToCartBtn addToCartBtn_inCart" data-id="<?=$p['id']?>"></div>
							</div>
						<?}?>
					</div>
				<?}?>
			</div>
		<?endif;?>
	</div>
<?}?>
<?if ($_REQUEST['AJAX_CART'] == 'Y'):?>
	<?die();?>
<?endif;?>
