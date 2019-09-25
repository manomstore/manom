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
 * @var string $templateFolder
 */

// unset($arResult['OFFERS']);
//echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
// echo "<pre style='text-align:left;'>";print_r($arResult['PROPERTIES']);echo "</pre>";
// echo "<pre style='text-align:left;'>";print_r($arResult['DATA_PROP_FOR_CHOICE']);echo "</pre>";
// echo "<pre style='text-align:left;'>";print_r($arResult['DISPLAY_OFFERS']);echo "</pre>";
// echo "<pre style='text-align:left;'>";print_r($arResult['OFFERS_BY_DISPLAY_PROP']);echo "</pre>";

// echo "<pre style='text-align:left;'>";print_r($arResult['PHOTOS']);echo "</pre>";
// unset($arResult['OFFERS']);
// unset($arResult['DISPLAY_OFFERS']);
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
// echo "<pre style='text-align:left;'>";print_r($arResult['PROPERTIES']);echo "</pre>";
if (!$_REQUEST['offer']){
	$actualOffer = $arResult['OFFERS_BY_DISPLAY_PROP'][0];
	if (!$actualOffer['props']) {
		foreach ($arResult['OFFERS_BY_DISPLAY_PROP'] as $k => $val) {
			if ($val['props']){
				$actualOffer = $val;
			}
		}
	}
} else {
	foreach ($arResult['OFFERS_BY_DISPLAY_PROP'] as $key => $value) {
		if ($value['id_offer'] == $_REQUEST['offer']){
			$actualOffer = $value;
		}
	}
	if (!$actualOffer) {
		$actualOffer = $arResult['OFFERS_BY_DISPLAY_PROP'][0];
	}
}
// echo "<pre style='text-align:left;'>";print_r($actualOffer);echo "</pre>";
?>

<!-- Каталог -->
<main class="product container">
	<div class="product-nav1">
		<h2 class="product-nav1__title isElementName"><?=$actualOffer['name']?></h2>
		<?if($actualOffer['difference_price']):?>
			<div class="p-nav-middle__sale active">Распродажа</div>
		<?endif;?>
		<div class="p-nav-top active">
            <label>
                <input class="p-nav-top__checkbox" type="checkbox" <?=checkProdInFavoriteAndCompareList($actualOffer['id_offer'], 'UF_FAVORITE_ID') ? 'checked' : '';?>>
                <div class="p-nav-top__favorite addToFavoriteList <?=!checkProdInFavoriteAndCompareList($actualOffer['id_offer'], 'UF_FAVORITE_ID') ? 'notActive' : '';?>" data-id='<?=$actualOffer['id_offer']?>' title="в избранное"></div>
            </label>
            <div class="p-nav-top__list addToCompareList <?=!checkProdInFavoriteAndCompareList($actualOffer['id_offer'], 'UF_COMPARE_ID') ? 'notActive' : '';?>" data-id='<?=$actualOffer['id_offer']?>'></div>
		</div>
	</div>
	<div class="product-nav2">
		<div class="product-credential">
			<?//if($arResult['PROPERTIES'][$arParams['TOP_FIELD_2_CODE']]['VALUE']){?>
				<span id="top_prop_code_prod"<?if(!$actualOffer['prod_code_top']):?> class="hidden_top_prop"<?endif;?>>
					Код товара: <span class="credential-code"><?=$actualOffer['prod_code_top']?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
			<?//}?>
			<?//if($actualOffer['article']){?>
				<span id="top_prop_model_prod"<?if(!$actualOffer['model_top']):?> class="hidden_top_prop"<?endif;?>>
					Модель: <span class="credential-code article_code_field"><?=$actualOffer['model_top']?></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</span>
			<?//}?>
			<?if($arResult['PROPERTIES'][$arParams['TOP_FIELD_3_CODE']]['VALUE']){?>
				<span class="credential-rostest"><?=$arResult['PROPERTIES'][$arParams['TOP_FIELD_3_CODE']]['VALUE']?></span> &nbsp;
			<?}?>
		</div>
		<?/*
		<div class="product-rating">
			<?for ($i=0; $i < 5; $i++) {
				if ($i >= $arResult['PRODUCT_RATING']) {
					?> <span> ★ </span> <?
				}else{
					?> ★ <?
				}
			}?>
		</div>
		<?
			$numberof = count($arResult['REVIEWS']);
			$value = 'отзыв';
			$suffix = array('', 'а', 'ов');

			function numberof($numberof, $value, $suffix) {
				$keys = array(2, 0, 1, 1, 1, 2);
				$mod = $numberof % 100;
				$suffix_key = $mod > 4 && $mod < 20 ? 2 : $keys[min($mod%10, 5)];

				return $value . $suffix[$suffix_key];
			}
		?>
		<div class="product-comments">
			<span><?=count($arResult['REVIEWS']);?></span>
			<?=numberof($numberof, 'отзыв', array('', 'а', 'ов'));?>
		</div>
		*/?>
	</div>
	<div class="product-main row">
		<div class="product-photo">
	<!-- 			<div class="product-photo__left col-1">
				<img class="active" src="img/4396801.jpg" data-fancybox="gallery-prod" data-color="Черный" alt="">
				<img src="img/4396801-red.jpg" data-fancybox="gallery-prod" data-color="Красный" alt="">
				<img src="img/4396801-white.jpg" data-fancybox="gallery-prod" data-color="Белый" alt="">
				<img src="img/submenu2.jpg" data-fancybox="gallery-prod" alt="">
				<img src="img/submenu6.jpg" data-fancybox="gallery-prod" alt="">
			</div>
			<div class="product-photo__right col-5">
				<img src="img/4396801.jpg" data-fancybox="gallery-prod" alt="">
			</div>	 -->
			<div class="product-photo__left col-1">
				<?/*foreach ($arResult['PHOTOS'] as $key => $photo) {
					if ($photo['is_offer'] and !$photo['is_more_photo']) {
						?><img
						<?if($actualOffer['photo_hash'] == $photo['hash_offer_color']):?>class="active"<?endif;?>
						<?if($key == 0 and !$actualOffer['photo_hash']):?>class="active"<?endif;?> src="<?=$photo['src']?>"
						data-color="<?=$photo['hash_offer_color']?>" alt=""><?
					} elseif ($photo['is_offer'] and $photo['is_more_photo']) {
						?>
						<img
						<?if($actualOffer['photo_hash'] == $photo['hash_offer_color']):?>class="mp-element mp-active"<?endif;?>
						<?if($actualOffer['photo_hash'] != $photo['hash_offer_color']):?>class="mp-element mp-disable"<?endif;?>
						data-color-mp="<?=$photo['hash_offer_color']?>"
						src="<?=$photo['src']?>">
						<?
					} else {
						?><img <?if($key == 0 and !$actualOffer['photo_hash']):?>class="active"<?endif;?> src="<?=$photo['src']?>"><?
					}
				}*/?>
				<?$findMainPhoto = false?>
				<?foreach ($arResult['PHOTOS'] as $key => $photo) {
					if(!$findMainPhoto) {
						if ($photo['is_offer']) {
							if ($actualOffer['photo_hash'] == $photo['hash_offer_color']){
								$arResult['PHOTOS'][$key]['is_main_photo'] = $photo['is_main_photo'] = true;
								$findMainPhoto = true;
							}
						} else {
							$arResult['PHOTOS'][$key]['is_main_photo'] = $photo['is_main_photo'] = true;
							$findMainPhoto = true;
						}
					}
					?>
						<img
						src="<?=$photo['src']?>"
						data-color="<?=$photo['hash_offer_color']?>"
						data-photo-id="<?=$photo['id']?>"
						class="
						<?=$photo['is_main_photo'] ? "active" : ""?>
						<?=$photo['is_offer'] ? "pp__is_offer" : "pp__is_prod no-display-img-detail"?>
						<?=($photo['is_offer'] && $actualOffer['photo_hash'] != $photo['hash_offer_color']) ? "pp__is_offer__disable" : ""?>
						"
						>
					<?}?>
			</div>
			<div class="product-photo__right col-5">
				<?/*foreach ($arResult['PHOTOS'] as $key => $photo) {
					?><a data-fancybox="gallery-prod" href="<?=$photo['src']?>">
						<?if($key == 0 and !$actualOffer['photo_hash']):?><img src="<?=$photo['src']?>" alt=""><?endif;?>
						<?if($actualOffer['photo_hash'] && $actualOffer['photo_hash'] == $photo['hash_offer_color'] && ($photo['is_offer'] and !$photo['is_more_photo'])):?>
							<img src="<?=$photo['src']?>" alt="">
						<?endif;?>
					</a><?
				}*/?>
				<?foreach ($arResult['PHOTOS'] as $key => $photo) {
					?>
					<a
					data-fancybox="
					<?=($photo['is_offer'] && $actualOffer['photo_hash'] == $photo['hash_offer_color']) ? "gallery-prod" : ""?>
					<?=!$photo['is_offer'] ? "gallery-prod" : ""?>
					"
					href="<?=$photo['src']?>"
					data-color="<?=$photo['hash_offer_color']?>"
					data-photo-id="<?=$photo['id']?>"
					class="
					pp__big_photo
					<?=$photo['is_main_photo'] ? "active" : ""?>
					<?=$photo['is_offer'] ? "pp__is_offer" : ""?>
					<?=($photo['is_offer'] && $actualOffer['photo_hash'] != $photo['hash_offer_color']) ? "pp__is_offer__disable" : ""?>
					"
					>
						<img src="<?=$photo['src']?>" alt="">
					</a>
					<?
				}?>
			</div>
			<?if($arResult['PROPERTIES'][$arParams['TEXT_UNDER_PHOTO_CODE']]['VALUE']):?>
				<div class="interesting-fact"><?=$arResult['PROPERTIES'][$arParams['TEXT_UNDER_PHOTO_CODE']]['~VALUE']['TEXT']?></div>
			<?endif;?>
		</div>
		<div class="product-content col-3">
            <?
                global $APPLICATION;
                $dir = $APPLICATION->GetCurDir();
                if(strstr($dir, '/catalog/utsenka/')){?>
                    <?if($arResult['PROPERTIES']['BS_STR']['~VALUE']) {?>
                        <div class="product-content__discount">
                            <p class="product-content__discount_text"><?=$arResult['PROPERTIES']['BS_STR']['~VALUE']['TEXT']?></p>
                        </div>
                    <?}?>
			<?}?>
			<?if($arResult['CAN_BUY'] == 'Y'):?>
				<div class="product-content__available">Товар в наличии</div>
			<?elseif($arResult['CAN_BUY'] == 'N'):?>
				<div class="product-content__available">Товар нет в наличии</div>
			<?endif;?>
			<? /*
<!--			<p class="prodBroke__title">Характеристики:</p>-->
			<div class="product-content__properties offersPropertiesList">
				<?foreach ($actualOffer['props'] as $key => $prop) {
					?>
                    <?if (!is_array($prop['title'])) {?>
                    <p class="product-content__value"><?=$prop['title']?></p>
                    <?}
				}?>

			</div>
			*/ ?>
			<p class="product-content__text">
					<?=$arResult['~PREVIEW_TEXT']?>
			</p>
			<? /*
			<a href="#product-tabs"	class="product-content__more">Подробнее...</a>
			*/ ?>
<!-- 				<p class="product-content__color">Выберите цвет: <span>Красный</span></p> -->
			<div>
                <div class="offers_by_prop_json" data-json='<?=htmlspecialchars(json_encode($arResult['OFFERS_BY_DISPLAY_PROP']))?>'></div>
            </div>
			<?if(count($arResult['DISPLAY_OFFERS']) > 1):?>
				<?foreach ($arResult['DATA_PROP_FOR_CHOICE'] as $key => $item) {
					?><div class="offers_prop" data-code="<?=$item['CODE']?>" data-id="<?=$item['ID']?>"><p class="product-content__color prop_title"><?=$item['TITLE']?>: <span><?=$actualOffer['props'][$item['CODE']]['title']?></span></p><?
					foreach ($item['VALUE'] as $k => $val) {
						if($val['img']){
							?><div
							class="offer_prop_item square-color square-black <?if($actualOffer['props'][$item['CODE']]['id'] == $val['id']):?>active<?endif;?>"
							data-color="<?=$val['hash_offer_color']?>"
							data-prop-code="<?=$item['CODE']?>"
							data-prop-id="<?=$item['ID']?>"
							data-id="<?=$val['id']?>"
							data-title="<?=$val['title']?>"
							style="background-image: url('<?=$val['img']?>');"
							></div><?
						} else {
							?><div class="offer_prop_item product-content__memory_item <?if($actualOffer['props'][$item['CODE']]['id'] == $val['id']):?>active<?endif;?>"
							data-prop-code="<?=$item['CODE']?>"
							data-prop-id="<?=$item['ID']?>"
							data-title="<?=$val['title']?>"
							data-id="<?=$val['id']?>"><?=$val['title']?></div><?
						}
					}
					?></div><?
				}?>
			<?endif;?>
		</div>
		<div class="product-sidebar col-3">

			<?if($arResult['CAN_BUY'] == 'Y'):?>
				<div class="product-sidebar__total mainBlockPrice">
	<!-- 					<div class="product-sidebar__total_count"><span>1</span> шт.
						<div class="product-sidebar__total_count-up"></div>
						<div class="product-sidebar__total_count-down"></div>
					</div> -->
					<div class="product-sidebar__total_price">
						<span class="product-sidebar__total-price-price addToCartBtn" data-id='<?=$actualOffer['id_offer']?>' ><?=$actualOffer['new_price']?></span>
						<span id="ruble"> ₽</span>
					</div>
					<div class="product-sidebar__right-price" style="display:<?=$actualOffer['difference_price'] ? 'block' : 'none'?>">
						<p class="product-sidebar__profit">Выгода <span><?=$actualOffer['difference_price']?></span> ₽</p>
						<p class="product-sidebar__old-price">Было <span><?=$actualOffer['old_price']?></span> ₽</p>
					</div>
				</div>
				<?
				$arBasketItems = array();

				$dbBasketItems = CSaleBasket::GetList(
				        array(
				                "NAME" => "ASC",
				                "ID" => "ASC"
				            ),
				        array(
				                "FUSER_ID" => CSaleBasket::GetBasketUserID(),
				                "LID" => SITE_ID,
				                "ORDER_ID" => "NULL"
				            ),
				        false,
				        false,
				        array("ID", "PRODUCT_ID")
				    );
				while ($arItems = $dbBasketItems->Fetch())
				{
				    $arBasketItems[md5($arItems["PRODUCT_ID"])] = $arItems["PRODUCT_ID"];
				}
				?>
				<a class="product-sidebar__button addToCartBtn addToCartBtn_mainPage<?=$arBasketItems[md5($actualOffer['id_offer'])] ? ' dsb-hidden' : '';?>" data-id="<?=$actualOffer['id_offer']?>"><?=GetMessage("CT_BCE_CATALOG_BUY");?></a>
				<?foreach ($arBasketItems as $s => $v) {?>
					<a class="product-sidebar__button goToFcnCart<?=$actualOffer['id_offer'] != $v ? ' dsb-hidden' : '';?>" href="/cart/" data-id="<?=$v?>">В корзину</a>
				<?}?>
				<div class="product-sidebar__buttons">
					<div class="product-sidebar__cheaper<?=!$arResult['CHEAPER'] ? ' product-sidebar__cheaper__disbled' : '' ;?>">Купить дешевле</div>
					<div class="product-sidebar__one-click BOC_btn" data-id="<?=$actualOffer['id_offer']?>" data-fancybox data-src="#popap-buy-one-click" href="javascript:;" >Купить в один клик</div>
				</div>
				<div id="popap-buy-one-click" class="popap-login">
					<h3 class="sci-login__title">Купить в один клик</h3>
					<form class="sci-login__form js-one-click-order">
						<div class="form_msg js-message-field"></div>
						<input type="hidden" name="productId" class="js-product-id" value="<?=$actualOffer['id_offer']?>">
						<label class="sci-login__label" for="sci-login__name_alt">Имя</label>
                        <input
                                type="text"
                                name="name"
                                value="<?= $arResult["CURRENT_USER"]["NAME"] ?>"
                                id="sci-login__name_alt"
                                class="sci-login__input"
                                placeholder="Ваше имя"
                                required
                        >

						<label class="sci-login__label" for="sci-login__tel_alt">Телефон</label>
						<input
                                type="tel"
                                name="phone"
                                value="<?= $arResult["CURRENT_USER"]["PHONE"] ?>"
                                id="sci-login__tel_alt"
                                class="sci-login__input"
                                placeholder="+7 (___) ___-__-__"
                                required
                        >

                        <?if (!$USER->IsAuthorized()):?>
						<label class="sci-login__label" for="sci-login__tel">E-mail</label>
						<input type="email" name="email" id="sci-login__tel" class="sci-login__input" placeholder="E-mail" required>
                        <?endif;?>
						<button class="sci-login__button">Купить</button>
					</form>
				</div>
			<?endif;?>
			<!-- <div class="product-sidebar__details">
				<p class="product-sidebar__details_name">Доставка:</p>
				<p class="product-sidebar__details_text">Адрес: Санкт-Петербург. Есенина 1 кв 67</p>
				<p class="product-sidebar__details_text">Курьер по вашему адресу (700 руб)</p>
			</div>
			<div class="product-sidebar__details">
				<p class="product-sidebar__details_name">Оплата:</p>
				<p class="product-sidebar__details_text">безналичный расчет</p>
			</div> -->
            <?if($arResult['PROPERTIES']['DELIVERY_DETAIL_CART']['VALUE']){?>
                <div class="product-sidebar__details">
                    <p class="product-sidebar__details_name">Доставка:</p>
                    <p class="product-sidebar__details_text"><?=$arResult['PROPERTIES']['DELIVERY_DETAIL_CART']['VALUE']?></p>
                </div>
            <?}?>
            <?if($arResult['PROPERTIES']['PAYMENT_DETAIL_CART']['VALUE']){?>
                <div class="product-sidebar__details">
                    <p class="product-sidebar__details_name">Оплата:</p>
                    <p class="product-sidebar__details_text"><?=$arResult['PROPERTIES']['PAYMENT_DETAIL_CART']['VALUE']?></p>
                </div>
            <?}?>
			<?if($arResult['PROPERTIES'][$arParams['GARANTY_CODE']]['VALUE']){?>
			<div class="product-sidebar__details">
				<p class="product-sidebar__details_name">Гарантия:</p>
				<p class="product-sidebar__details_text"><?=$arResult['PROPERTIES'][$arParams['GARANTY_CODE']]['VALUE']?></p>
			</div>
			<?}?>

			<? /*
			<div class="social">
				<span>Поделись с друзьями: </span>
                <a href="#" class="social-link">
                    <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/s-instagram.png" alt="">
                </a>
                <a href="https://t.me/share/url?url=http//<?=$_SERVER['HTTP_HOST'] . $arResult['DETAIL_PAGE_URL']?>" class="social-link">
                    <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/s-telegram.png" alt="">
                </a>
                <a href="https://www.facebook.com/sharer.php?u=http://<?=$_SERVER['HTTP_HOST'] . $arResult['DETAIL_PAGE_URL']?>" class="social-link">
                    <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/s-facebook.png" alt="">
                </a>
                <a href="https://vk.com/share.php?url=http://<?=$_SERVER['HTTP_HOST'] . $arResult['DETAIL_PAGE_URL']?>" class="social-link">
                    <img src="<?=SITE_TEMPLATE_PATH?>/assets/img/s-vk.png" alt="">
                </a>
				</span>
			</div>
			*/ ?>
		</div>
	</div>
<!--	<div class="product-kit">-->
<!--		<h2 class="product-kit__title">Купить в комплекте со скидкой</h2>-->
<!--		<div class="product-kit__compile">Собрать свой комплект</div>-->
<!--		<div class="product-kit__block row">-->
<!--			<div class="product-kit__column col-3">-->
<!--				<div class="preview-prod link">-->
<!--					<img src="img/pr-prod1.png" alt="">-->
<!--					<div class="preview-prod__descr">-->
<!--						<h3 class="preview-prod__name">-->
<!--							Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple-->
<!--						</h3>-->
<!--						<div class="preview-prod-bottom">-->
<!--							<div class="preview-prod-bottom__price">5 000<span> ₽</span>	</div>-->
<!--							<div class="preview-prod-bottom__old-price">5 700</div>-->
<!--						</div>-->
<!--					</div>-->
<!--				</div>-->
<!--				<div class="preview-prod link">-->
<!--					<img src="img/pr-prod1.png" alt="">-->
<!--					<div class="preview-prod__descr">-->
<!--						<h3 class="preview-prod__name">-->
<!--							Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple-->
<!--						</h3>-->
<!--						<div class="preview-prod-bottom">-->
<!--							<div class="preview-prod-bottom__price">5 000<span> ₽</span>	</div>-->
<!--							<div class="preview-prod-bottom__old-price">5 700</div>-->
<!--						</div>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--			<div class="product-kit__column col-3">-->
<!--				<div class="preview-prod link plus">-->
<!--					<img src="img/pr-prod1.png" alt="">-->
<!--					<div class="preview-prod__descr">-->
<!--						<h3 class="preview-prod__name">-->
<!--							Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple-->
<!--						</h3>-->
<!--						<div class="preview-prod-bottom">-->
<!--							<div class="preview-prod-bottom__price">5 000<span> ₽</span>	</div>-->
<!--							<div class="preview-prod-bottom__old-price">5 700</div>-->
<!--						</div>-->
<!--					</div>-->
<!--				</div>-->
<!--				<div class="preview-prod link plus">-->
<!--					<img src="img/pr-prod1.png" alt="">-->
<!--					<div class="preview-prod__descr">-->
<!--						<h3 class="preview-prod__name">-->
<!--							Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple-->
<!--						</h3>-->
<!--						<div class="preview-prod-bottom">-->
<!--							<div class="preview-prod-bottom__price">5 000<span> ₽</span>	</div>-->
<!--							<div class="preview-prod-bottom__old-price">5 700</div>-->
<!--						</div>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--			<div class="product-kit__column col-3">-->
<!--				<div class="preview-prod link plus">-->
<!--					<img src="img/pr-prod1.png" alt="">-->
<!--					<div class="preview-prod__descr">-->
<!--						<h3 class="preview-prod__name">-->
<!--							Смартфон Apple iPhone SE 32Gb Space Gray MP822RU/A, серый Смартфон Apple, серый Смартфон Apple, серый Смартфон Apple-->
<!--						</h3>-->
<!--						<div class="preview-prod-bottom">-->
<!--							<div class="preview-prod-bottom__price">5 000<span> ₽</span>	</div>-->
<!--							<div class="preview-prod-bottom__old-price">5 700</div>-->
<!--						</div>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--			<div class="product-kit__column col-3">-->
<!--				<div class="product-kit__result">-->
<!--					<div class="product-kit__price"><span>250 000</span> ₽-->
<!--						<p class="product-kit__old-price">400 000</p>-->
<!--					</div>-->
<!--					<a href="#" class="product-kit__button">Купить комплект</a>-->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
	<!-- Табы -->
	<div id="product-tabs" class="product-tabs">
		<input id="tab2" type="radio" name="tabs" checked>
		<label for="tab2"><span>Описание</span></label>
		<input id="tab1" type="radio" name="tabs" >
		<label for="tab1"><span>Характеристики</span></label>
		<?if ($arResult['REVIEWS']){?>
			<input id="tab3" type="radio" name="tabs">
			<label for="tab3"><span>Отзывы<br>покупателей</span></label>
		<?}?>
        <? if (!empty($arResult['QNA_VALUES'])): ?>
            <input id="tab4" type="radio" name="tabs">
            <label for="tab4"><span>Вопрос-<br>ответ</span></label>
        <? endif; ?>
		<?if ($arResult['DELIV']){?>
			<input id="tab5" type="radio" name="tabs">
			<label for="tab5"><span>Оплата<br>и доставка</span></label>
		<?}?>
		<?if ($arResult['PROPERTIES'][$arParams['YOUTUBE_CODE']]['VALUE']){?>
			<input id="tab6" type="radio" name="tabs">
			<label for="tab6"><span>Обзоры<br>на товар</span></label>
		<?}?>
		<?if ($arResult['PROPERTIES'][$arParams['ACCESS_PROD_CODE']]['VALUE']){?>
			<input id="tab7" type="radio" name="tabs">
			<label for="tab7"><span>Аксессуары</span></label>
		<?}?>
		<?if ($arResult['CHEAPER']){?>
			<input id="tab8" type="radio" name="tabs">
			<label for="tab8"><span>Купить<br>дешевле</span></label>
		<?}?>


		<section id="content2">
			<div class="tab-content row">
                <?if($arResult['PROPERTIES']['BUNDLE_BOX']['~VALUE']['TEXT']) {?>
                    <div class="tab-content__column right-block">
                        <div class="bundle-text">Комплект поставки</div>
                        <p class="tab-content__text1 bundle-border">
                            <?=$arResult['PROPERTIES']['BUNDLE_BOX']['~VALUE']['TEXT']?>
                        </p>
                    </div>
                <?}?>
				<div class="tab-content__column <?=$arResult['PROPERTIES']['BUNDLE_BOX']['~VALUE']['TEXT']? 'left-block' : ''?>">
					<p class="tab-content__text1">
						<?=$arResult['DETAIL_TEXT']?>
					</p>
				</div>

			</div>
		</section>
		<section id="content1">
			<div class="tab-content row">
				<!-- <div class="tab-content__column col-6"> -->
                <div class="instruction-wrapper col-4">
                <?
                if($arResult['PROPERTIES'][$arParams['FILES_CODE']]['VALUE']){
                    ?><strong class="name-properties col-12">Инструкции и сертификаты:</strong><br><?
                    foreach ($arResult['PROPERTIES'][$arParams['FILES_CODE']]['VALUE'] as $key => $value) {
                        $rsFile = CFile::GetByID($value);
                        $arFile = $rsFile->Fetch();
                        $fileHref = CFile::GetPath($value);
                        $splitFileName = explode('.', $arFile['ORIGINAL_NAME']);
                        $fileType = array_pop($splitFileName);
                        $fileName = implode(' ', $splitFileName);
                        ?>
                        <div class="tab-content__column" style="padding-bottom: 10px;">
                            <div class="row">
                                <div class="col-1" style="text-align: center;">
                                    <a href="<?=$fileHref?>" target="_blank"><img src="/upload/pdfs-512.png" alt=""></i></a>
                                </div>
                                <div class="col-9">
                                    <a href="<?=$fileHref?>" target="_blank" style="color: #000; font-weight: 700;"><?=$fileName?></a><br>
                                    <a href="<?=$fileHref?>" target="_blank" style="color: #000;">(<?=formatBytes($arFile['FILE_SIZE'])?>)</a>
                                </div>
                            </div>
                        </div>
                        <?
                        // echo "<pre style='text-align:left;'>";print_r($arFile);echo "</pre>";
                    }
                }
                function formatBytes($size, $precision = 2)
                {
                    $base = log($size, 1024);
                    $suffixes = array('', 'кб.', 'мб.', 'гб.', 'тб.');

                    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
                }
                ?>
                </div>
                <div class="characteristics-wrapper col-8">
					<?$APPLICATION->IncludeComponent("redsign:grupper.list", "catalog_element",
						Array(
							"DISPLAY_PROPERTIES" => $arResult["DISPLAY_PROPERTIES"],	// Свойства
							"CACHE_TIME" => "36000",	// Время кеширования (сек.)
						),
						false
					);?>
                </div>
						<?/*$count = 0;$secondCol = false;?>
						<?foreach($arResult['DISPLAY_PROPERTIES'] as $i => $prop):
							$count++?>
							<p class="tab-content__item">
								<span class="tab-content__title"><?=$prop['NAME']?>: </span>
								<span class="tab-content__text"><?=$prop['DISPLAY_VALUE']?></span>
							</p>
							<?if(((count($arResult['DISPLAY_PROPERTIES'])/2) <= $count) and $secondCol == false){
								$secondCol = true;
								?></div><div class="tab-content__column col-6"><?
							}?>
						<?endforeach;*/?>
					<!-- <p class="tab-content__item">
						<span class="tab-content__title">Экран ноутбука: </span>
						<span class="tab-content__text">Диагональ экрана в дюймах	15.6", разрешение экрана 1366×768</span>
					</p>
					<p class="tab-content__item">
						<span class="tab-content__title">Конфигурация ноутбука: </span>
						<span class="tab-content__text">Процессор	Intel Celeron 1000M,	двухъядерный, оперативная память	2048 Мб, графический контроллер	Intel HD Graphics HD 4000</span>
					</p>
					<p class="tab-content__item">
						<span class="tab-content__title">Устройства хранения данных: </span>
						<span class="tab-content__text">Объем HDD	500 Гб, дисковод DVD-RW</span>
					</p>
					<p class="tab-content__item">
						<span class="tab-content__title">Корпус ноутбука: </span>
						<span class="tab-content__text">Пластик, цвет на выбор:	черный, белый, серебристый</span>
					</p>
				</div>
				<div class="tab-content__column col-6">
					<p class="tab-content__item">
						<span class="tab-content__text">Коммуникации ноутбука: </span>
						<span class="tab-content__text">Wi-Fiб Bluetooth v2.0</span>
					</p>
					<p class="tab-content__item">
						<span class="tab-content__text">Операционная система ноутбука: </span>
						<span class="tab-content__text">Windows 8</span>
					</p>
					<p class="tab-content__item">
						<span class="tab-content__text">Мультимедийные особенности: </span>
						<span class="tab-content__text">Веб-камера	встроенная</span>
					</p>
					<p class="tab-content__item">
						<span class="tab-content__text">Батарея ноутбука: </span>
						<span class="tab-content__text">Количество ячеек батареи 6 cell</span>
					</p>
					<p class="tab-content__item">
						<span class="tab-content__text">Гарантия:	</span>
						<span class="tab-content__text">12 месяцев</span>
					</p> -->
				<!-- </div> -->

				<?//echo "<pre style='text-align:left;'>";print_r($arResult['PROPERTIES'][$arParams['FILES_CODE']]);echo "</pre>";?>

				<div class="tab-content__column col">
					<!-- <p class="tab-content__item">
						<span class="tab-content__title">Комплектация:</span>
						<span class="tab-content__text">ноутбук Lenovo IdeaPad 320-15ISK, блок питания 220В, руководство пользователя, гарантийная сервисная книжка, коробка.</span>
					</p> -->
					<?/*<p class="tab-content__text1">
						<?=$arResult['DETAIL_TEXT']?>
					</p>*/?>
				</div>
			</div>
		</section>
		<?/*<section id="content2">
			<div class="tab-content row">
				<div class="tab-content__column col">
					<!-- <p class="tab-content__item">
						<span class="tab-content__title">Гарантия: </span>
						<span class="tab-content__text">12 месяцев</span>
					</p> -->
					<?if($arResult['PROPERTIES'][$arParams['GARANTY_CODE']]['VALUE']){?>
					<p class="tab-content__item">
						<span class="tab-content__title">Гарантия:</span>
						<span class="tab-content__text"><?=$arResult['PROPERTIES'][$arParams['GARANTY_CODE']]['VALUE']?></span>
					</p>
					<?}?>
					<?$APPLICATION->IncludeFile("/include_area/garanty.php",Array(),Array("MODE"=>"html"));?>
				</div>
			</div>
		</section>*/?>

		<section id="content3">
			<div class="tab-content row">
				<div class="tab-content__column col">
                    <? foreach ($arResult['REVIEWS'] as $key => $value) { ?>
                        <p class="tab-content__item">
							<span class="tab-content__title"><?= $value['date'] ?>
                                <?= !empty($value["author"]) ? $value["author"] . "." : "" ?>
                                <?= !empty($value["source"]) ? $value["source"] . "." : "" ?>
                            </span>
                            <span class="tab-content__text1"><?= $value['review_text'] ?></span>
	                        <? if (!empty($value['merits'])): ?>
		                        <span class="tab-content__text1">Достоинства: <?= $value['merits'] ?></span>
	                        <? endif; ?>
	                        <? if (!empty($value['disadvantages'])): ?>
		                        <span class="tab-content__text1">Недостатки: <?= $value['disadvantages'] ?></span>
	                        <? endif; ?>
                        </p>
                    <? } ?>

					<!-- <p class="tab-content__item">
						<span class="tab-content__title">01.01.2018г. Максим Галкин: </span>
						<span class="tab-content__text1">Обалденный Ноутбук! Подарил жене - она в восторге! Диагональ экрана в дюймах	15.6", разрешение экрана 1366×768. Далеко-далеко за словесными горами в стране, гласных и согласных живут рыбные тексты. Составитель то безопасную заглавных. Несколько безопасную даль текста вскоре сих!</span>
					</p>
					<p class="tab-content__item">
						<span class="tab-content__title">01.01.2018г. Кристина Орбакайте: </span>
						<span class="tab-content__text1">Обалденный Ноутбук! Подарила маме - она в восторге! Диагональ экрана в дюймах	15.6", разрешение экрана 1366×768. Далеко-далеко за словесными горами в стране, гласных и согласных живут рыбные тексты. Составитель то безопасную заглавных. Несколько безопасную даль текста вскоре сих!</span>
					</p>
					<p class="tab-content__item">
						<span class="tab-content__title">01.01.2018г. Владимир Пресняков: </span>
						<span class="tab-content__text1">Обалденный Ноутбук! Подарил бывшей теще - она в восторге! Диагональ экрана в дюймах	15.6", разрешение экрана 1366×768. Далеко-далеко за словесными горами в стране, гласных и согласных живут рыбные тексты. Составитель то безопасную заглавных. Несколько безопасную даль текста вскоре сих!</span>
					</p>
					<p class="tab-content__item">
						<span class="tab-content__title">01.01.2018г. Алла Борисовна: </span>
						<span class="tab-content__text1">Обалденный Ноутбук! Подарила себе на Новый год - просто в восторге! Диагональ экрана в дюймах	15.6", разрешение экрана 1366×768. Далеко-далеко за словесными горами в стране, гласных и согласных живут рыбные тексты. Составитель то безопасную заглавных. Несколько безопасную даль текста вскоре сих!</span>
					</p> -->
				</div>
			</div>
		</section>
        <? if (!empty($arResult['QNA_VALUES'])): ?>
            <section id="content4">
                <div class="tab-content row">
                    <div class="tab-content__column col">
                        <? foreach ($arResult['QNA_VALUES'] as $key => $value) {
                            ?>
                            <p class="tab-content__item">
                                <span class="tab-content__title"><?= $value['title'] ?></span>
                                <span class="tab-content__text1"><?= $value['answer'] ?></span>
                            </p>
                            <?
                        } ?>
                    </div>
                </div>
            </section>
        <? endif; ?>
		<section id="content5">
			<div class="tab-content row">
				<div class="tab-content__column col">
					<?foreach ($arResult['DELIV'] as $key => $value) {?>
						<p class="tab-content__item">
							<span class="tab-content__title"><?=$value['title']?></span>
							<span class="tab-content__text1"><?=$value['text']?></span>
						</p>
					<?}?>
					<!-- <p class="tab-content__item">
						<span class="tab-content__title">По Москве и Петербургу мы доставляем собственной курьерской службой.</span>
						<span class="tab-content__text1">Далеко-далеко за словесными горами в стране, гласных и согласных живут рыбные тексты. Правилами взобравшись, реторический сих ipsum обеспечивает, рыбными не наш за послушавшись большой пустился вершину дал текста речью рекламных назад. Проектах.</span>
					</p>
					<p class="tab-content__item">
						<span class="tab-content__title">В Москве и Петербурге есть пункты самовывоза.</span>
						<span class="tab-content__text1">Далеко-далеко за словесными горами в стране, гласных и согласных живут рыбные тексты. Правилами взобравшись, реторический сих ipsum обеспечивает, рыбными не наш за послушавшись большой пустился вершину дал текста речью рекламных назад. Проектах.</span>
					</p>
					<p class="tab-content__item">
						<span class="tab-content__title">В регионы мы отправляем транспортными компаниями.</span>
						<span class="tab-content__text1">Стоимость доставки варьируется в зависимости от транспортной компании и города доставки. После заполнения данных в корзине вы сможете выбрать лучшую транспортную компанию. </span>
					</p> -->
				</div>
			</div>
		</section>
		<section id="content6">
			<div class="tab-content row">
				<?foreach ($arResult['PROPERTIES'][$arParams['YOUTUBE_CODE']]['VALUE'] as $key => $value) {?>
					<div class="tab-content__column col-6">
						<div class="video-container">
							<iframe width="100%" height="100%" src="<?=$value?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
						</div>
					</div>
				<?}?>
				<!-- <div class="tab-content__column col-6">
					<div class="video-container">
						<iframe width="100%" height="100%" src="https://www.youtube.com/embed/60VYXd9RVJ0?autohide=1&rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
				</div>
				<div class="tab-content__column col-6">
					<div class="video-container">
						<iframe width="100%" height="100%" src="https://www.youtube.com/embed/HisL83eANwM?autohide=1&rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
				</div> -->
			</div>
		</section>
		<section id="content7">
			<?if ($arResult['PROPERTIES'][$arParams['ACCESS_PROD_CODE']]['VALUE']):?>
				<?
					global $filterAcess;
					$filterAcess = array('ID' => $arResult['PROPERTIES'][$arParams['ACCESS_PROD_CODE']]['VALUE']);
				?>
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "access", Array(
							"ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
							"ADD_PICT_PROP" => "-",	// Дополнительная картинка основного товара
							"ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
							"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
							"ADD_TO_BASKET_ACTION" => "ADD",	// Показывать кнопку добавления в корзину или покупки
							"AJAX_MODE" => "N",	// Включить режим AJAX
							"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
							"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
							"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
							"AJAX_OPTION_STYLE" => "N",	// Включить подгрузку стилей
							"BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
							"BASKET_URL" => "/personal/basket.php",	// URL, ведущий на страницу с корзиной покупателя
							"BRAND_PROPERTY" => "BRAND_REF",
							"BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
							"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
							"CACHE_GROUPS" => "N",	// Учитывать права доступа
							"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
							"CACHE_TYPE" => "N",	// Тип кеширования
							"COMPATIBLE_MODE" => "Y",	// Включить режим совместимости
							"CONVERT_CURRENCY" => "Y",	// Показывать цены в одной валюте
							"CURRENCY_ID" => "RUB",	// Валюта, в которую будут сконвертированы цены
							"CUSTOM_FILTER" => "",
							"DATA_LAYER_NAME" => "dataLayer",
							"DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
							"DISABLE_INIT_JS_IN_COMPONENT" => "N",	// Не подключать js-библиотеки в компоненте
							"DISCOUNT_PERCENT_POSITION" => "bottom-right",	// Расположение процента скидки
							"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
							"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
							"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
							"ELEMENT_SORT_FIELD2" => "id",	// Поле для второй сортировки элементов
							"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
							"ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки элементов
							"ENLARGE_PRODUCT" => "PROP",	// Выделять товары в списке
							"ENLARGE_PROP" => "-",	// Выделять по выбранному свойству
							"FILTER_NAME" => "filterAcess",	// Имя массива со значениями фильтра для фильтрации элементов
							"HIDE_NOT_AVAILABLE" => "Y",	// Недоступные товары
							"HIDE_NOT_AVAILABLE_OFFERS" => "Y",	// Недоступные торговые предложения
							"IBLOCK_ID" => "6",	// Инфоблок
							"IBLOCK_TYPE" => "catalog",	// Тип инфоблока
							"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
							"LABEL_PROP" => "",	// Свойства меток товара
							"LABEL_PROP_MOBILE" => "",
							"LABEL_PROP_POSITION" => "top-left",
							"LAZY_LOAD" => "N",	// Показать кнопку ленивой загрузки Lazy Load
							"LINE_ELEMENT_COUNT" => "3",	// Количество элементов выводимых в одной строке таблицы
							"LOAD_ON_SCROLL" => "N",	// Подгружать товары при прокрутке до конца
							"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
							"MESS_BTN_ADD_TO_BASKET" => "В корзину",	// Текст кнопки "Добавить в корзину"
							"MESS_BTN_BUY" => "Купить",	// Текст кнопки "Купить"
							"MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
							"MESS_BTN_LAZY_LOAD" => "Показать ещё",
							"MESS_BTN_SUBSCRIBE" => "Подписаться",	// Текст кнопки "Уведомить о поступлении"
							"MESS_NOT_AVAILABLE" => "Нет в наличии",	// Сообщение об отсутствии товара
							"META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
							"META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
							"OFFERS_CART_PROPERTIES" => array(	// Свойства предложений, добавляемые в корзину
								0 => "COLOR_REF",
							),
							"OFFERS_FIELD_CODE" => array(	// Поля предложений
								0 => "",
								1 => "",
							),
							"OFFERS_LIMIT" => "1",	// Максимальное количество предложений для показа (0 - все)
							"OFFERS_PROPERTY_CODE" => array(	// Свойства предложений
								0 => "BS_STR",
								1 => "HDD",
								2 => "OS",
								3 => "PROC",
								4 => "SCREEN_RES",
								5 => "COLOR_REF",
								6 => "",
							),
							"OFFERS_SORT_FIELD" => "sort",	// По какому полю сортируем предложения товара
							"OFFERS_SORT_FIELD2" => "id",	// Поле для второй сортировки предложений товара
							"OFFERS_SORT_ORDER" => "asc",	// Порядок сортировки предложений товара
							"OFFERS_SORT_ORDER2" => "desc",	// Порядок второй сортировки предложений товара
							"OFFER_ADD_PICT_PROP" => "-",	// Дополнительные картинки предложения
							"OFFER_TREE_PROPS" => array(	// Свойства для отбора предложений
								0 => "COLOR_REF",
							),
							"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
							"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
							"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
							"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
							"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
							"PAGER_TEMPLATE" => "header-search",	// Шаблон постраничной навигации
							"PAGER_TITLE" => "Товары",	// Название категорий
							"PAGE_ELEMENT_COUNT" => "6",	// Количество элементов на странице
							"PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
							"PRICE_CODE" => "",	// Тип цены
							"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
							"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",	// Порядок отображения блоков товара
							"PRODUCT_DISPLAY_MODE" => "Y",	// Схема отображения
							"PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
							"PRODUCT_PROPERTIES" => "",	// Характеристики товара
							"PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
							"PRODUCT_QUANTITY_VARIABLE" => "",	// Название переменной, в которой передается количество товара
							"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",	// Вариант отображения товаров
							"PRODUCT_SUBSCRIPTION" => "Y",	// Разрешить оповещения для отсутствующих товаров
							"PROPERTY_CODE" => array(	// Свойства
								0 => "BLOG_POST_ID",
								1 => "ACESS",
								2 => "TOP_FIELD_1",
								3 => "BATTARY",
								4 => "A_N_Q",
								5 => "GARANTY",
								6 => "TOP_FIELD_2",
								7 => "BLOG_COMMENTS_CNT",
								8 => "LAPTOP_COMUNICATION",
								9 => "EQUIPMENT",
								10 => "LAPTOP_CONFIGURATIONS",
								11 => "LAPTOP_CASE",
								12 => "BUY_CHEAPER",
								13 => "MULTIMEDIA",
								14 => "TEXT_UNDER_PHOTO",
								15 => "OS",
								16 => "ACESS_STR",
								17 => "SUREFACE",
								18 => "TOP_FIELD_3",
								19 => "SCREEN_RESOLUTION",
								20 => "SELL_PROD",
								21 => "YOUTUBE",
								22 => "PRODUCT_OF_THE_DAY",
								23 => "HHD",
								24 => "SCREEN_OF_LAPTOP",
								25 => "",
							),
							"PROPERTY_CODE_MOBILE" => array(	// Свойства товаров, отображаемые на мобильных устройствах
								0 => "BLOG_POST_ID",
								1 => "ACESS",
								2 => "TOP_FIELD_1",
								3 => "BATTARY",
								4 => "A_N_Q",
								5 => "GARANTY",
								6 => "TOP_FIELD_2",
								7 => "BLOG_COMMENTS_CNT",
								8 => "LAPTOP_COMUNICATION",
								9 => "EQUIPMENT",
								10 => "LAPTOP_CONFIGURATIONS",
								11 => "LAPTOP_CASE",
								12 => "BUY_CHEAPER",
								13 => "MULTIMEDIA",
								14 => "TEXT_UNDER_PHOTO",
								15 => "OS",
								16 => "ACESS_STR",
								17 => "SUREFACE",
								18 => "TOP_FIELD_3",
								19 => "SCREEN_RESOLUTION",
								20 => "SELL_PROD",
								21 => "YOUTUBE",
								22 => "PRODUCT_OF_THE_DAY",
								23 => "HHD",
								24 => "SCREEN_OF_LAPTOP",
							),
							"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],	// Параметр ID продукта (для товарных рекомендаций)
							"RCM_TYPE" => "personal",	// Тип рекомендации
							"SECTION_CODE" => "",	// Код раздела
							"SECTION_ID" => "",	// ID раздела
							"SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
							"SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
							"SECTION_USER_FIELDS" => array(	// Свойства раздела
								0 => "",
								1 => "",
							),
							"SEF_MODE" => "N",	// Включить поддержку ЧПУ
							"SET_BROWSER_TITLE" => "N",	// Устанавливать заголовок окна браузера
							"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
							"SET_META_DESCRIPTION" => "N",	// Устанавливать описание страницы
							"SET_META_KEYWORDS" => "N",	// Устанавливать ключевые слова страницы
							"SET_STATUS_404" => "N",	// Устанавливать статус 404
							"SET_TITLE" => "N",	// Устанавливать заголовок страницы
							"SHOW_404" => "N",	// Показ специальной страницы
							"SHOW_ALL_WO_SECTION" => "Y",	// Показывать все элементы, если не указан раздел
							"SHOW_CLOSE_POPUP" => "N",	// Показывать кнопку продолжения покупок во всплывающих окнах
							"SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки
							"SHOW_FROM_SECTION" => "N",	// Показывать товары из раздела
							"SHOW_MAX_QUANTITY" => "N",	// Показывать остаток товара
							"SHOW_OLD_PRICE" => "N",	// Показывать старую цену
							"SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
							"SHOW_SLIDER" => "N",	// Показывать слайдер для товаров
							"SLIDER_INTERVAL" => "3000",
							"SLIDER_PROGRESS" => "N",
							"TEMPLATE_THEME" => "blue",	// Цветовая тема
							"USE_ENHANCED_ECOMMERCE" => "N",	// Отправлять данные электронной торговли в Google и Яндекс
							"USE_MAIN_ELEMENT_SECTION" => "N",	// Использовать основной раздел для показа элемента
							"USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
							"USE_PRODUCT_QUANTITY" => "N",	// Разрешить указание количества товара
							"COMPONENT_TEMPLATE" => "header-search",
							"DISPLAY_COMPARE" => "N",	// Разрешить сравнение товаров
						),
						false
					);?>
			<?endif;?>

		</section>
		<section id="content8">
			<?if ($arResult['CHEAPER']):?>
				<?
					global $filterCheaper;
					$filterCheaper = array('ID' => $arResult['CHEAPER']);
				?>
				<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "access", Array(
							"ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
							"ADD_PICT_PROP" => "-",	// Дополнительная картинка основного товара
							"ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
							"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
							"ADD_TO_BASKET_ACTION" => "ADD",	// Показывать кнопку добавления в корзину или покупки
							"AJAX_MODE" => "N",	// Включить режим AJAX
							"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
							"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
							"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
							"AJAX_OPTION_STYLE" => "N",	// Включить подгрузку стилей
							"BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
							"BASKET_URL" => "/personal/basket.php",	// URL, ведущий на страницу с корзиной покупателя
							"BRAND_PROPERTY" => "BRAND_REF",
							"BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
							"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
							"CACHE_GROUPS" => "N",	// Учитывать права доступа
							"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
							"CACHE_TYPE" => "N",	// Тип кеширования
							"COMPATIBLE_MODE" => "Y",	// Включить режим совместимости
							"CONVERT_CURRENCY" => "Y",	// Показывать цены в одной валюте
							"CURRENCY_ID" => "RUB",	// Валюта, в которую будут сконвертированы цены
							"CUSTOM_FILTER" => "",
							"DATA_LAYER_NAME" => "dataLayer",
							"DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
							"DISABLE_INIT_JS_IN_COMPONENT" => "N",	// Не подключать js-библиотеки в компоненте
							"DISCOUNT_PERCENT_POSITION" => "bottom-right",	// Расположение процента скидки
							"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
							"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
							"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
							"ELEMENT_SORT_FIELD2" => "id",	// Поле для второй сортировки элементов
							"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
							"ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки элементов
							"ENLARGE_PRODUCT" => "PROP",	// Выделять товары в списке
							"ENLARGE_PROP" => "-",	// Выделять по выбранному свойству
							"FILTER_NAME" => "filterCheaper",	// Имя массива со значениями фильтра для фильтрации элементов
							"HIDE_NOT_AVAILABLE" => "Y",	// Недоступные товары
							"HIDE_NOT_AVAILABLE_OFFERS" => "Y",	// Недоступные торговые предложения
							"IBLOCK_ID" => "6",	// Инфоблок
							"IBLOCK_TYPE" => "catalog",	// Тип инфоблока
							"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
							"LABEL_PROP" => "",	// Свойства меток товара
							"LABEL_PROP_MOBILE" => "",
							"LABEL_PROP_POSITION" => "top-left",
							"LAZY_LOAD" => "N",	// Показать кнопку ленивой загрузки Lazy Load
							"LINE_ELEMENT_COUNT" => "3",	// Количество элементов выводимых в одной строке таблицы
							"LOAD_ON_SCROLL" => "N",	// Подгружать товары при прокрутке до конца
							"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
							"MESS_BTN_ADD_TO_BASKET" => "В корзину",	// Текст кнопки "Добавить в корзину"
							"MESS_BTN_BUY" => "Купить",	// Текст кнопки "Купить"
							"MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
							"MESS_BTN_LAZY_LOAD" => "Показать ещё",
							"MESS_BTN_SUBSCRIBE" => "Подписаться",	// Текст кнопки "Уведомить о поступлении"
							"MESS_NOT_AVAILABLE" => "Нет в наличии",	// Сообщение об отсутствии товара
							"META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
							"META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
							"OFFERS_CART_PROPERTIES" => array(	// Свойства предложений, добавляемые в корзину
								0 => "COLOR_REF",
							),
							"OFFERS_FIELD_CODE" => array(	// Поля предложений
								0 => "",
								1 => "",
							),
							"OFFERS_LIMIT" => "1",	// Максимальное количество предложений для показа (0 - все)
							"OFFERS_PROPERTY_CODE" => array(	// Свойства предложений
								0 => "BS_STR",
								1 => "HDD",
								2 => "OS",
								3 => "PROC",
								4 => "SCREEN_RES",
								5 => "COLOR_REF",
								6 => "",
							),
							"OFFERS_SORT_FIELD" => "sort",	// По какому полю сортируем предложения товара
							"OFFERS_SORT_FIELD2" => "id",	// Поле для второй сортировки предложений товара
							"OFFERS_SORT_ORDER" => "asc",	// Порядок сортировки предложений товара
							"OFFERS_SORT_ORDER2" => "desc",	// Порядок второй сортировки предложений товара
							"OFFER_ADD_PICT_PROP" => "-",	// Дополнительные картинки предложения
							"OFFER_TREE_PROPS" => array(	// Свойства для отбора предложений
								0 => "COLOR_REF",
							),
							"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
							"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
							"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
							"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
							"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
							"PAGER_TEMPLATE" => "header-search",	// Шаблон постраничной навигации
							"PAGER_TITLE" => "Товары",	// Название категорий
							"PAGE_ELEMENT_COUNT" => "6",	// Количество элементов на странице
							"PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
							"PRICE_CODE" => "",	// Тип цены
							"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
							"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",	// Порядок отображения блоков товара
							"PRODUCT_DISPLAY_MODE" => "Y",	// Схема отображения
							"PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
							"PRODUCT_PROPERTIES" => "",	// Характеристики товара
							"PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
							"PRODUCT_QUANTITY_VARIABLE" => "",	// Название переменной, в которой передается количество товара
							"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",	// Вариант отображения товаров
							"PRODUCT_SUBSCRIPTION" => "Y",	// Разрешить оповещения для отсутствующих товаров
							"PROPERTY_CODE" => array(	// Свойства
								0 => "BLOG_POST_ID",
								1 => "ACESS",
								2 => "TOP_FIELD_1",
								3 => "BATTARY",
								4 => "A_N_Q",
								5 => "GARANTY",
								6 => "TOP_FIELD_2",
								7 => "BLOG_COMMENTS_CNT",
								8 => "LAPTOP_COMUNICATION",
								9 => "EQUIPMENT",
								10 => "LAPTOP_CONFIGURATIONS",
								11 => "LAPTOP_CASE",
								12 => "BUY_CHEAPER",
								13 => "MULTIMEDIA",
								14 => "TEXT_UNDER_PHOTO",
								15 => "OS",
								16 => "ACESS_STR",
								17 => "SUREFACE",
								18 => "TOP_FIELD_3",
								19 => "SCREEN_RESOLUTION",
								20 => "SELL_PROD",
								21 => "YOUTUBE",
								22 => "PRODUCT_OF_THE_DAY",
								23 => "HHD",
								24 => "SCREEN_OF_LAPTOP",
								25 => "BS_STR",
							),
							"PROPERTY_CODE_MOBILE" => array(	// Свойства товаров, отображаемые на мобильных устройствах
								0 => "BLOG_POST_ID",
								1 => "ACESS",
								2 => "TOP_FIELD_1",
								3 => "BATTARY",
								4 => "A_N_Q",
								5 => "GARANTY",
								6 => "TOP_FIELD_2",
								7 => "BLOG_COMMENTS_CNT",
								8 => "LAPTOP_COMUNICATION",
								9 => "EQUIPMENT",
								10 => "LAPTOP_CONFIGURATIONS",
								11 => "LAPTOP_CASE",
								12 => "BUY_CHEAPER",
								13 => "MULTIMEDIA",
								14 => "TEXT_UNDER_PHOTO",
								15 => "OS",
								16 => "ACESS_STR",
								17 => "SUREFACE",
								18 => "TOP_FIELD_3",
								19 => "SCREEN_RESOLUTION",
								20 => "SELL_PROD",
								21 => "YOUTUBE",
								22 => "PRODUCT_OF_THE_DAY",
								23 => "HHD",
								24 => "SCREEN_OF_LAPTOP",
							),
							"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],	// Параметр ID продукта (для товарных рекомендаций)
							"RCM_TYPE" => "personal",	// Тип рекомендации
							"SECTION_CODE" => "",	// Код раздела
							"SECTION_ID" => "",	// ID раздела
							"SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
							"SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
							"SECTION_USER_FIELDS" => array(	// Свойства раздела
								0 => "",
								1 => "",
							),
							"SEF_MODE" => "N",	// Включить поддержку ЧПУ
							"SET_BROWSER_TITLE" => "N",	// Устанавливать заголовок окна браузера
							"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
							"SET_META_DESCRIPTION" => "N",	// Устанавливать описание страницы
							"SET_META_KEYWORDS" => "N",	// Устанавливать ключевые слова страницы
							"SET_STATUS_404" => "N",	// Устанавливать статус 404
							"SET_TITLE" => "N",	// Устанавливать заголовок страницы
							"SHOW_404" => "N",	// Показ специальной страницы
							"SHOW_ALL_WO_SECTION" => "Y",	// Показывать все элементы, если не указан раздел
							"SHOW_CLOSE_POPUP" => "N",	// Показывать кнопку продолжения покупок во всплывающих окнах
							"SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки
							"SHOW_FROM_SECTION" => "N",	// Показывать товары из раздела
							"SHOW_MAX_QUANTITY" => "N",	// Показывать остаток товара
							"SHOW_OLD_PRICE" => "N",	// Показывать старую цену
							"SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
							"SHOW_SLIDER" => "N",	// Показывать слайдер для товаров
							"SLIDER_INTERVAL" => "3000",
							"SLIDER_PROGRESS" => "N",
							"TEMPLATE_THEME" => "blue",	// Цветовая тема
							"USE_ENHANCED_ECOMMERCE" => "N",	// Отправлять данные электронной торговли в Google и Яндекс
							"USE_MAIN_ELEMENT_SECTION" => "N",	// Использовать основной раздел для показа элемента
							"USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
							"USE_PRODUCT_QUANTITY" => "N",	// Разрешить указание количества товара
							"COMPONENT_TEMPLATE" => "header-search",
							"DISPLAY_COMPARE" => "N",	// Разрешить сравнение товаров
						),
						false
					);?>
			<?endif;?>
		</section>

	</div>

</main>
<?if ($arResult['PROPERTIES'][$arParams['RECOM_CODE']]['VALUE']):?>
	<?
		global $filterAcess;
		$filterAcess = array('ID' => $arResult['PROPERTIES'][$arParams['RECOM_CODE']]['VALUE']);
	?>
	<?$APPLICATION->IncludeComponent("bitrix:catalog.section", "recom", Array(
				"ACTION_VARIABLE" => "action",	// Название переменной, в которой передается действие
				"ADD_PICT_PROP" => "-",	// Дополнительная картинка основного товара
				"ADD_PROPERTIES_TO_BASKET" => "Y",	// Добавлять в корзину свойства товаров и предложений
				"ADD_SECTIONS_CHAIN" => "N",	// Включать раздел в цепочку навигации
				"ADD_TO_BASKET_ACTION" => "ADD",	// Показывать кнопку добавления в корзину или покупки
				"AJAX_MODE" => "N",	// Включить режим AJAX
				"AJAX_OPTION_ADDITIONAL" => "",	// Дополнительный идентификатор
				"AJAX_OPTION_HISTORY" => "N",	// Включить эмуляцию навигации браузера
				"AJAX_OPTION_JUMP" => "N",	// Включить прокрутку к началу компонента
				"AJAX_OPTION_STYLE" => "N",	// Включить подгрузку стилей
				"BACKGROUND_IMAGE" => "-",	// Установить фоновую картинку для шаблона из свойства
				"BASKET_URL" => "/personal/basket.php",	// URL, ведущий на страницу с корзиной покупателя
				"BRAND_PROPERTY" => "BRAND_REF",
				"BROWSER_TITLE" => "-",	// Установить заголовок окна браузера из свойства
				"CACHE_FILTER" => "N",	// Кешировать при установленном фильтре
				"CACHE_GROUPS" => "N",	// Учитывать права доступа
				"CACHE_TIME" => "36000000",	// Время кеширования (сек.)
				"CACHE_TYPE" => "N",	// Тип кеширования
				"COMPATIBLE_MODE" => "Y",	// Включить режим совместимости
				"CONVERT_CURRENCY" => "Y",	// Показывать цены в одной валюте
				"CURRENCY_ID" => "RUB",	// Валюта, в которую будут сконвертированы цены
				"CUSTOM_FILTER" => "",
				"DATA_LAYER_NAME" => "dataLayer",
				"DETAIL_URL" => "",	// URL, ведущий на страницу с содержимым элемента раздела
				"DISABLE_INIT_JS_IN_COMPONENT" => "N",	// Не подключать js-библиотеки в компоненте
				"DISCOUNT_PERCENT_POSITION" => "bottom-right",	// Расположение процента скидки
				"DISPLAY_BOTTOM_PAGER" => "N",	// Выводить под списком
				"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
				"ELEMENT_SORT_FIELD" => "sort",	// По какому полю сортируем элементы
				"ELEMENT_SORT_FIELD2" => "id",	// Поле для второй сортировки элементов
				"ELEMENT_SORT_ORDER" => "asc",	// Порядок сортировки элементов
				"ELEMENT_SORT_ORDER2" => "desc",	// Порядок второй сортировки элементов
				"ENLARGE_PRODUCT" => "PROP",	// Выделять товары в списке
				"ENLARGE_PROP" => "-",	// Выделять по выбранному свойству
				"FILTER_NAME" => "filterAcess",	// Имя массива со значениями фильтра для фильтрации элементов
				"HIDE_NOT_AVAILABLE" => "Y",	// Недоступные товары
				"HIDE_NOT_AVAILABLE_OFFERS" => "Y",	// Недоступные торговые предложения
				"IBLOCK_ID" => "6",	// Инфоблок
				"IBLOCK_TYPE" => "catalog",	// Тип инфоблока
				"INCLUDE_SUBSECTIONS" => "Y",	// Показывать элементы подразделов раздела
				"LABEL_PROP" => "",	// Свойства меток товара
				"LABEL_PROP_MOBILE" => "",
				"LABEL_PROP_POSITION" => "top-left",
				"LAZY_LOAD" => "N",	// Показать кнопку ленивой загрузки Lazy Load
				"LINE_ELEMENT_COUNT" => "3",	// Количество элементов выводимых в одной строке таблицы
				"LOAD_ON_SCROLL" => "N",	// Подгружать товары при прокрутке до конца
				"MESSAGE_404" => "",	// Сообщение для показа (по умолчанию из компонента)
				"MESS_BTN_ADD_TO_BASKET" => "В корзину",	// Текст кнопки "Добавить в корзину"
				"MESS_BTN_BUY" => "Купить",	// Текст кнопки "Купить"
				"MESS_BTN_DETAIL" => "Подробнее",	// Текст кнопки "Подробнее"
				"MESS_BTN_LAZY_LOAD" => "Показать ещё",
				"MESS_BTN_SUBSCRIBE" => "Подписаться",	// Текст кнопки "Уведомить о поступлении"
				"MESS_NOT_AVAILABLE" => "Нет в наличии",	// Сообщение об отсутствии товара
				"META_DESCRIPTION" => "-",	// Установить описание страницы из свойства
				"META_KEYWORDS" => "-",	// Установить ключевые слова страницы из свойства
				"OFFERS_CART_PROPERTIES" => array(	// Свойства предложений, добавляемые в корзину
					0 => "COLOR_REF",
				),
				"OFFERS_FIELD_CODE" => array(	// Поля предложений
					0 => "",
					1 => "",
				),
				"OFFERS_LIMIT" => "1",	// Максимальное количество предложений для показа (0 - все)
				"OFFERS_PROPERTY_CODE" => array(	// Свойства предложений
					0 => "BS_STR",
					1 => "HDD",
					2 => "OS",
					3 => "PROC",
					4 => "SCREEN_RES",
					5 => "COLOR_REF",
					6 => "",
				),
				"OFFERS_SORT_FIELD" => "sort",	// По какому полю сортируем предложения товара
				"OFFERS_SORT_FIELD2" => "id",	// Поле для второй сортировки предложений товара
				"OFFERS_SORT_ORDER" => "asc",	// Порядок сортировки предложений товара
				"OFFERS_SORT_ORDER2" => "desc",	// Порядок второй сортировки предложений товара
				"OFFER_ADD_PICT_PROP" => "-",	// Дополнительные картинки предложения
				"OFFER_TREE_PROPS" => array(	// Свойства для отбора предложений
					0 => "COLOR_REF",
				),
				"PAGER_BASE_LINK_ENABLE" => "N",	// Включить обработку ссылок
				"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
				"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
				"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
				"PAGER_TEMPLATE" => "header-search",	// Шаблон постраничной навигации
				"PAGER_TITLE" => "Товары",	// Название категорий
				"PAGE_ELEMENT_COUNT" => "6",	// Количество элементов на странице
				"PARTIAL_PRODUCT_PROPERTIES" => "N",	// Разрешить добавлять в корзину товары, у которых заполнены не все характеристики
				"PRICE_CODE" => "",	// Тип цены
				"PRICE_VAT_INCLUDE" => "Y",	// Включать НДС в цену
				"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",	// Порядок отображения блоков товара
				"PRODUCT_DISPLAY_MODE" => "Y",	// Схема отображения
				"PRODUCT_ID_VARIABLE" => "id",	// Название переменной, в которой передается код товара для покупки
				"PRODUCT_PROPERTIES" => "",	// Характеристики товара
				"PRODUCT_PROPS_VARIABLE" => "prop",	// Название переменной, в которой передаются характеристики товара
				"PRODUCT_QUANTITY_VARIABLE" => "",	// Название переменной, в которой передается количество товара
				"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':true}]",	// Вариант отображения товаров
				"PRODUCT_SUBSCRIPTION" => "Y",	// Разрешить оповещения для отсутствующих товаров
				"PROPERTY_CODE" => array(	// Свойства
					0 => "BLOG_POST_ID",
					1 => "ACESS",
					2 => "TOP_FIELD_1",
					3 => "BATTARY",
					4 => "A_N_Q",
					5 => "GARANTY",
					6 => "TOP_FIELD_2",
					7 => "BLOG_COMMENTS_CNT",
					8 => "LAPTOP_COMUNICATION",
					9 => "EQUIPMENT",
					10 => "LAPTOP_CONFIGURATIONS",
					11 => "LAPTOP_CASE",
					12 => "BUY_CHEAPER",
					13 => "MULTIMEDIA",
					14 => "TEXT_UNDER_PHOTO",
					15 => "OS",
					16 => "ACESS_STR",
					17 => "SUREFACE",
					18 => "TOP_FIELD_3",
					19 => "SCREEN_RESOLUTION",
					20 => "SELL_PROD",
					21 => "YOUTUBE",
					22 => "PRODUCT_OF_THE_DAY",
					23 => "HHD",
					24 => "SCREEN_OF_LAPTOP",
					25 => "",
				),
				"PROPERTY_CODE_MOBILE" => array(	// Свойства товаров, отображаемые на мобильных устройствах
					0 => "BLOG_POST_ID",
					1 => "ACESS",
					2 => "TOP_FIELD_1",
					3 => "BATTARY",
					4 => "A_N_Q",
					5 => "GARANTY",
					6 => "TOP_FIELD_2",
					7 => "BLOG_COMMENTS_CNT",
					8 => "LAPTOP_COMUNICATION",
					9 => "EQUIPMENT",
					10 => "LAPTOP_CONFIGURATIONS",
					11 => "LAPTOP_CASE",
					12 => "BUY_CHEAPER",
					13 => "MULTIMEDIA",
					14 => "TEXT_UNDER_PHOTO",
					15 => "OS",
					16 => "ACESS_STR",
					17 => "SUREFACE",
					18 => "TOP_FIELD_3",
					19 => "SCREEN_RESOLUTION",
					20 => "SELL_PROD",
					21 => "YOUTUBE",
					22 => "PRODUCT_OF_THE_DAY",
					23 => "HHD",
					24 => "SCREEN_OF_LAPTOP",
				),
				"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],	// Параметр ID продукта (для товарных рекомендаций)
				"RCM_TYPE" => "personal",	// Тип рекомендации
				"SECTION_CODE" => "",	// Код раздела
				"SECTION_ID" => "",	// ID раздела
				"SECTION_ID_VARIABLE" => "SECTION_ID",	// Название переменной, в которой передается код группы
				"SECTION_URL" => "",	// URL, ведущий на страницу с содержимым раздела
				"SECTION_USER_FIELDS" => array(	// Свойства раздела
					0 => "",
					1 => "",
				),
				"SEF_MODE" => "N",	// Включить поддержку ЧПУ
				"SET_BROWSER_TITLE" => "N",	// Устанавливать заголовок окна браузера
				"SET_LAST_MODIFIED" => "N",	// Устанавливать в заголовках ответа время модификации страницы
				"SET_META_DESCRIPTION" => "N",	// Устанавливать описание страницы
				"SET_META_KEYWORDS" => "N",	// Устанавливать ключевые слова страницы
				"SET_STATUS_404" => "N",	// Устанавливать статус 404
				"SET_TITLE" => "N",	// Устанавливать заголовок страницы
				"SHOW_404" => "N",	// Показ специальной страницы
				"SHOW_ALL_WO_SECTION" => "Y",	// Показывать все элементы, если не указан раздел
				"SHOW_CLOSE_POPUP" => "N",	// Показывать кнопку продолжения покупок во всплывающих окнах
				"SHOW_DISCOUNT_PERCENT" => "Y",	// Показывать процент скидки
				"SHOW_FROM_SECTION" => "N",	// Показывать товары из раздела
				"SHOW_MAX_QUANTITY" => "N",	// Показывать остаток товара
				"SHOW_OLD_PRICE" => "N",	// Показывать старую цену
				"SHOW_PRICE_COUNT" => "1",	// Выводить цены для количества
				"SHOW_SLIDER" => "N",	// Показывать слайдер для товаров
				"SLIDER_INTERVAL" => "3000",
				"SLIDER_PROGRESS" => "N",
				"TEMPLATE_THEME" => "blue",	// Цветовая тема
				"USE_ENHANCED_ECOMMERCE" => "N",	// Отправлять данные электронной торговли в Google и Яндекс
				"USE_MAIN_ELEMENT_SECTION" => "N",	// Использовать основной раздел для показа элемента
				"USE_PRICE_COUNT" => "N",	// Использовать вывод цен с диапазонами
				"USE_PRODUCT_QUANTITY" => "N",	// Разрешить указание количества товара
				"COMPONENT_TEMPLATE" => "header-search",
				"DISPLAY_COMPARE" => "N",	// Разрешить сравнение товаров
			),
			false
		);?>
<?endif;?>
