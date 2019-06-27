<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
global $glob_sectionInfo, $new_offer_filter;
// echo "<pre style='text-align:left;'>";print_r($arResult['ITEMS'][0]);echo "</pre>";
?>
<?$arParams = $arParams['glob_params'];?>
<section class="compare container">
  <div class="compare__main">
    <aside class="personal__aside">
      <h1 class="personal__title">Личный кабинет</h1>
      <a href="personal.html" id="personal-nav__item1" class="personal-nav__item personal-nav__name" data-id="pb-info">Мои настройки</a>
      <p class="personal-nav__name">Покупки:</p>
      <a href="personal.html" id="personal-nav__item2" class="personal-nav__item" data-id="pb-info">История покупок</a>
      <a href="personal.html" id="personal-nav__item3" class="personal-nav__item" data-id="pb-favour">Товары в избранном</a>
      <a href="compare.html" id="personal-nav__item4" class="personal-nav__item">Сравнение товаров</a>
      <p class="personal-nav__name">Моя активность:</p>
      <a href="personal.html" id="personal-nav__item4" class="personal-nav__item" data-id="pb-comments">Мои отзывы</a>
    </aside>

    <?if ($arResult['ITEMS']) {?>
    <!-- <h2 class="compare-h2">Сравнение товаров</h2> -->
    <div class="compare__wrap">
      <h2 class="compare-h2">Сравнение товаров</h2>
      <div class="row compare__block">
        <?foreach ($arResult['ITEMS'] as $key => $arItems) {?>
          <?$arCanBuy = CCatalogSKU::IsExistOffers($arItems['ID'], $arResult['IBLOCK_ID']);?>
          <?
          $arPrice = CCatalogProduct::GetOptimalPrice($arItems['ID'], 1, $USER->GetUserGroupArray(), 'N');
          if (!$arPrice || count($arPrice) <= 0)
          {
              if ($nearestQuantity = CCatalogProduct::GetNearestQuantityPrice($arItems['ID'], $key, $USER->GetUserGroupArray()))
              {
                  $quantity = $nearestQuantity;
                  $arPrice = CCatalogProduct::GetOptimalPrice($productID, $quantity, $USER->GetUserGroupArray(), $renewal);
              }
          }
          ?>
          <div class="col4 compare-page-item" data-id="<?=$arPrice['PRODUCT_ID']?>">
            <div class="product-card border <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>">
              <div class="product-card__img">
                <?foreach ($arItems['PROPERTIES']['MORE_PHOTO']['VALUE'] as $val => $img ) {?>
                    <?if ($arItems['PROPERTIES']['MORE_PHOTO']['VALUE']) {
                        $file = CFile::ResizeImageGet($img, array('width'=>350, 'height'=>350), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    }?>
                    <div class="product-card__slide">
                        <img src="<?=$file['src']?>" alt="">
                    </div>
                <?}?>
              </div>
              <p class="p-label-top active">
                <?if ($arItems['PROPERTIES']['PRODUCT_OF_THE_DAY']['VALUE'] == 'Да') {?>
                  Товар дня
                <?}?>
              </p>
              <div class="p-nav-top">
                  <label>
                      <input class="p-nav-top__checkbox" type="checkbox" <?=checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'checked' : '';?>>
                      <div class="p-nav-top__favorite addToFavoriteList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_FAVORITE_ID') ? 'notActive' : '';?>" data-id='<?=$arPrice['PRODUCT_ID']?>' title="в избранное"></div>
                  </label>
              </div>
              <div class="p-nav-middle">
                  <?if ($arItems['PROPERTIES']['SELL_PROD']['VALUE']){?>
                      <div class="p-nav-middle__sale active">
                          <?=$arItems['PROPERTIES']['SELL_PROD']['NAME']?>
                      </div>
                  <?}?>
                  <div class="p-nav-middle__rating">
                    <?for ($i=0; $i < 5; $i++) {
                      if ($i >= $arResult['REVIEW'][$arItems['ID']]['rating']) {
                        ?> <span> ★ </span> <?
                      }else{
                        ?> ★ <?
                      }
                    }?></div>
                  <div class="p-nav-middle__comments"><span><?=$arResult['REVIEW'][$arItems['ID']]['count']?></span></div>
              </div>
              <h3 class="p-name">
                <a href="<?=$arItems['DETAIL_PAGE_URL']?>"><?=$arItems['NAME']?></a>
              </h3>
              <div class="p-nav-bottom">
                <div class="p-nav-bottom__price">
                  <?=$arPrice['DISCOUNT_PRICE']?><span> ₽</span>
                  <?if($arPrice['DISCOUNT_PRICE'] < $arPrice['BASE_PRICE']):?>
                    <div class="p-nav-bottom__oldprice"><?=$arPrice['BASE_PRICE']?></div>
                  <?endif;?>
                </div>
                <div class="p-nav-bottom__shopcart <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'addToCartBtn' : ''?>" data-id='<?=$arPrice['PRODUCT_ID']?>' <?=$arItems['CATALOG_QUANTITY'] > 0 || $arCanBuy == 1 ? 'enable' : 'disable'?>></div>
              </div>
              <div class="product-content">
                <div class="p-cart-properties cb-line-properties">
                  <?foreach ($arItems['DISPLAY_PROPERTIES'] as $c => $prop) {?>
                    <p>
                      <span class="p-cart-properties__name"><?=$prop['NAME']?></span>
                      <span class="p-cart-properties__value bgreen"><?=is_array($prop['DISPLAY_VALUE']) ? implode(",", $prop['DISPLAY_VALUE']) : $prop['DISPLAY_VALUE'];?></span>
                    </p>
                  <?}?>
                  <?$componentElementParams = array(
              			'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
              			'IBLOCK_ID' => $arParams['IBLOCK_ID'],
              			'PROPERTY_CODE' => $arParams['DETAIL_PROPERTY_CODE'],
              			'META_KEYWORDS' => $arParams['DETAIL_META_KEYWORDS'],
              			'META_DESCRIPTION' => $arParams['DETAIL_META_DESCRIPTION'],
              			'BROWSER_TITLE' => $arParams['DETAIL_BROWSER_TITLE'],
              			'SET_CANONICAL_URL' => $arParams['DETAIL_SET_CANONICAL_URL'],
              			'BASKET_URL' => $arParams['BASKET_URL'],
              			'ACTION_VARIABLE' => $arParams['ACTION_VARIABLE'],
              			'PRODUCT_ID_VARIABLE' => $arParams['PRODUCT_ID_VARIABLE'],
              			'SECTION_ID_VARIABLE' => $arParams['SECTION_ID_VARIABLE'],
              			'CHECK_SECTION_ID_VARIABLE' => (isset($arParams['DETAIL_CHECK_SECTION_ID_VARIABLE']) ? $arParams['DETAIL_CHECK_SECTION_ID_VARIABLE'] : ''),
              			'PRODUCT_QUANTITY_VARIABLE' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
              			'PRODUCT_PROPS_VARIABLE' => $arParams['PRODUCT_PROPS_VARIABLE'],
              			'CACHE_TYPE' => $arParams['CACHE_TYPE'],
              			'CACHE_TIME' => $arParams['CACHE_TIME'],
              			'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
              			'SET_TITLE' => $arParams['SET_TITLE'],
              			'SET_LAST_MODIFIED' => $arParams['SET_LAST_MODIFIED'],
              			'MESSAGE_404' => $arParams['~MESSAGE_404'],
              			'SET_STATUS_404' => $arParams['SET_STATUS_404'],
              			'SHOW_404' => $arParams['SHOW_404'],
              			'FILE_404' => $arParams['FILE_404'],
              			'PRICE_CODE' => $arParams['~PRICE_CODE'],
              			'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
              			'SHOW_PRICE_COUNT' => $arParams['SHOW_PRICE_COUNT'],
              			'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_INCLUDE'],
              			'PRICE_VAT_SHOW_VALUE' => $arParams['PRICE_VAT_SHOW_VALUE'],
              			'USE_PRODUCT_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
              			'PRODUCT_PROPERTIES' => $arParams['PRODUCT_PROPERTIES'],
              			'ADD_PROPERTIES_TO_BASKET' => (isset($arParams['ADD_PROPERTIES_TO_BASKET']) ? $arParams['ADD_PROPERTIES_TO_BASKET'] : ''),
              			'PARTIAL_PRODUCT_PROPERTIES' => (isset($arParams['PARTIAL_PRODUCT_PROPERTIES']) ? $arParams['PARTIAL_PRODUCT_PROPERTIES'] : ''),
              			'LINK_IBLOCK_TYPE' => $arParams['LINK_IBLOCK_TYPE'],
              			'LINK_IBLOCK_ID' => $arParams['LINK_IBLOCK_ID'],
              			'LINK_PROPERTY_SID' => $arParams['LINK_PROPERTY_SID'],
              			'LINK_ELEMENTS_URL' => $arParams['LINK_ELEMENTS_URL'],

              			'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
              			'OFFERS_FIELD_CODE' => $arParams['DETAIL_OFFERS_FIELD_CODE'],
              			'OFFERS_PROPERTY_CODE' => $arParams['DETAIL_OFFERS_PROPERTY_CODE'],
              			'OFFERS_SORT_FIELD' => $arParams['OFFERS_SORT_FIELD'],
              			'OFFERS_SORT_ORDER' => $arParams['OFFERS_SORT_ORDER'],
              			'OFFERS_SORT_FIELD2' => $arParams['OFFERS_SORT_FIELD2'],
              			'OFFERS_SORT_ORDER2' => $arParams['OFFERS_SORT_ORDER2'],

              			'ELEMENT_ID' => $arItems['PROPERTIES']['CML2_LINK']['VALUE'],
              		);

              		$elementId = $APPLICATION->IncludeComponent(
              			'bitrix:catalog.element',
              			'compare',
              			$componentElementParams
              		);?>
                </div>
              </div>
              <span class="compare__basket hidden-remove" style="display:none;"></span>
              <div class="compare__basket addToCompareList <?=!checkProdInFavoriteAndCompareList($arPrice['PRODUCT_ID'], 'UF_COMPARE_ID') ? 'notActive' : '';?>" data-id='<?=$arPrice['PRODUCT_ID']?>'>Удалить из сравнения</div>
            </div>
          </div>
        <?}?>
      </div>
    </div>
    <?}else{?>
        <p class="notetext">Нет товаров для сравнения</p>
    <?}?>
  </div>
</section>
