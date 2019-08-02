<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}
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
<?= $_REQUEST['show_fi'] ? '' : 'display: none;'; ?>
  ">
  <pre><? print_r($arResult['ITEMS'][0]); ?></pre>
</div>
<? if ($_REQUEST['AJAX_MIN_FAVORITE'] == 'Y'): ?>
	<? $APPLICATION->RestartBuffer(); ?>
<? endif; ?>
<div class="top-personal__block">
  <a href="/user/favorite/" class="top-personal__link" id="mini_favorite_header_counter">
    <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/icons/heart.svg" alt="Иконка избранного"
         width="17"
         height="15">
    <span class="top-count"><?= count($arResult['ITEMS']); ?></span>
  </a>
  <!-- preview-heart -->
	<? $prodCount = 0; ?>
  <div class="personal-preview" id="mini_favorite_header">
    <div class="personal-preview__wrapper">
      <div class="personal-preview__top">
        <h2 class="personal-preview__title">Избранное</h2>
        <button class="personal-preview__link" type="button">Очистить</button>
      </div>
			<? if ($arResult['ITEMS']): ?>
				<? foreach ($arResult['ITEMS'] as $key => $value) { ?>
					<? $prodCount++; ?>
					<? if ($prodCount > 5) {
						break;
					} ?>
          <div class="preview-prod" data-cart-item="<?= $value['ID'] ?>">
            <div class="preview-prod__picture">
              <img src="<?= CFile::GetPath($value['PROPERTIES']['MORE_PHOTO']['VALUE'][0]); ?>" alt="Изображение товара">
            </div>
            <div class="preview-prod__descr">
              <div class="preview-prod-bottom">
                <div class="preview-prod-bottom__price">
									<?= number_format($value['MIN_PRICE']['DISCOUNT_VALUE_VAT'], 0, '.', ' ') ?><span> ₽</span>
                </div>
                <button class="preview-prod-bottom__del" type="button" aria-label="Удалить товар" data-cart-item="<?= $value['ID'] ?>"></button>
                <!--            <label>-->
                <!--              <input class="preview-prod-bottom__checkbox" type="checkbox" checked>-->
                <!--              <span class="preview-prod-bottom__button preview-prod-bottom__button-favorite" data-cart-item="-->
								<? //=$value['ID']?><!--"></span>-->
                <!--            </label>-->
              </div>
              <h3 class="preview-prod__name">
                <a href="<?= $value['~DETAIL_PAGE_URL'] ?>"><?= $value['NAME'] ?></a>
              </h3>
            </div>
          </div>
				<? } ?>
				<? if (count($arResult['ITEMS']) > 5): ?>
          <p style="text-align: left;padding: 5px 10px;">Товаров: <?= count($arResult['ITEMS']); ?></p>
				<? endif; ?>
        <div class="preview-bottom">
          <a href="/user/favorite/" class="preview-bottom__button preview-bottom__compare">В избранное</a>
        </div>
			<? endif; ?>
    </div>
  </div>
</div>
<? if ($_REQUEST['AJAX_MIN_FAVORITE'] == 'Y'): ?>
	<? die(); ?>
<? endif; ?>
