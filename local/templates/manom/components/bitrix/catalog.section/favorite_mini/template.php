<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
global $glob_sectionInfo, $new_offer_filter;
// echo "<pre style='text-align:left;'>";print_r($arResult['ITEMS'][0]);echo "</pre>";
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
color: #fff;
text-align: left;
<?=$_REQUEST['show_fi'] ? '' : 'display: none;';?>
">
	<pre><?print_r($arResult['ITEMS'][0]);?></pre>
</div>
<?if ($_REQUEST['AJAX_MIN_FAVORITE'] == 'Y'):?>
  <?$APPLICATION->RestartBuffer();?>
<?endif;?>
<a href="/user/favorite/" class="top-personal__heart" id="mini_favorite_header_counter">
  <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/top-heart.svg" alt="">
  <span class="top-count"><?=count($arResult['ITEMS']);?></span>
</a>
<!-- preview-heart -->
<?$prodCount = 0;?>
<div class="preview-heart" id="mini_favorite_header">
	<?if(!$arResult['ITEMS']):?>
		<p class="preview-heart-not-text">Нет товара</p>
	<?else:?>
    <?foreach ($arResult['ITEMS'] as $key => $value) {?>
			<?$prodCount++;?>
			<?if($prodCount>5) break;?>
      <div class="preview-prod preview-prods" data-cart-item="<?=$value['ID']?>">
        <img src="<?=CFile::GetPath($value['PROPERTIES']['MORE_PHOTO']['VALUE'][0]);?>" alt="">
        <div class="preview-prod__descr">
          <h3 class="preview-prod__name">
            <a href="<?=$value['~DETAIL_PAGE_URL']?>"><?=$value['NAME']?></a>
          </h3>
          <div class="preview-prod-bottom">
            <div class="preview-prod-bottom__price">
              <?=number_format($value['MIN_PRICE']['DISCOUNT_VALUE_VAT'], 0, '.', ' ')?><span> ₽</span>
            </div>
            <label>
              <input class="preview-prod-bottom__checkbox" type="checkbox" checked>
              <span class="preview-prod-bottom__button preview-prod-bottom__button-favorite" data-cart-item="<?=$value['ID']?>"></span>
            </label>
          </div>
        </div>
      </div>
    <?}?>
			<?if (count($arResult['ITEMS']) > 5):?>
				<div class="hr"></div>
				<p style="text-align: left;padding: 5px 10px;">Товаров: <?=count($arResult['ITEMS']);?></p>
			<?endif;?>
      <a href="/user/favorite/" class="preview-bottom__button preview-bottom__compare">В избранное</a>
	<?endif;?>
</div>
<?if ($_REQUEST['AJAX_MIN_FAVORITE'] == 'Y'):?>
  <?die();?>
<?endif;?>
