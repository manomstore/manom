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
<?= $_REQUEST['show_ci'] ? '' : 'display: none;'; ?>
  ">
  <pre><? print_r($arResult['ITEMS'][0]); ?></pre>
</div>
<? if ($_REQUEST['AJAX_MIN_COMPARE'] == 'Y'): ?>
	<? $APPLICATION->RestartBuffer(); ?>
<? endif; ?>
<div class="top-personal__block top-personal__block--compare">
    <a <?= count($arResult['ITEMS']) > 0 ? "href='/catalog/compare/'" : "" ?>
            class="top-personal__link"
            id="mini_compare_header_counter">
    <img src="<?= SITE_TEMPLATE_PATH ?>/assets/img/icons/compare.svg" alt="Иконка сравнения" width="16" height="15">
	  <? if (count($arResult['ITEMS']) !== 0): ?>
      <span class="top-count"><?= count($arResult['ITEMS']); ?></span>
	  <? endif; ?>
  </a>
  <!-- preview-heart -->
	<? $prodCount = 0; ?>
  <div class="personal-preview" id="mini_compare_header">
    <div class="personal-preview__wrapper">
      <div class="personal-preview__top">
        <h2 class="personal-preview__title">Сравнение</h2>
        <button class="personal-preview__link js-clear-compare" type="button">Очистить</button>
      </div>
			<? if ($arResult['ITEMS']): ?>
				<? foreach ($arResult['ITEMS'] as $key => $value) { ?>
					<? $prodCount++; ?>
					<? if ($prodCount > 5) {
						continue;
					} ?>
          <div class="preview-prod" data-cart-item="<?= $value['ID'] ?>">
            <div class="preview-prod__picture">
              <img src="<?= CFile::GetPath($value['PROPERTIES']['MORE_PHOTO']['VALUE'][0]); ?>" alt="Изображение товара">
            </div>
            <div class="preview-prod__descr">
              <div class="preview-prod-bottom">
                <div class="preview-prod-bottom__price">
                  <span class="preview-prod-bottom__value preview-prod-bottom__value--new">
                    <?= number_format($value['MIN_PRICE']['DISCOUNT_VALUE_VAT'], 0, '.', ' ') ?> ₽
                  </span>
                  <span class="preview-prod-bottom__value preview-prod-bottom__value--sale">
                    999 999 ₽
                  </span>
                </div>
                <button class="preview-prod-bottom__del preview-prod-bottom__button-compare" type="button" aria-label="Удалить товар" data-cart-item="<?= $value['ID'] ?>"></button>
                <!--                <label>-->
                <!--                  <input class="preview-prod-bottom__checkbox" type="checkbox" checked>-->
                <!--                  <span class="preview-prod-bottom__button preview-prod-bottom__button-compare" data-cart-item="-->
								<? //= $value['ID'] ?><!--"></span>-->
                <!--                </label>-->
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
          <a href="/catalog/compare/" class="preview-bottom__button preview-bottom__compare">В сравнение</a>
        </div>
			<? endif; ?>
    </div>
  </div>
</div>
<? if ($_REQUEST['AJAX_MIN_COMPARE'] == 'Y'): ?>
	<? die(); ?>
<? endif; ?>
