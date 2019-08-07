<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

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
<? if ($_REQUEST['AJAX_CART'] == 'Y'): ?>
    <? $APPLICATION->RestartBuffer(); ?>
<? endif; ?>
<? if (count($arResult['GRID']['ROWS']) <= 0): ?>
    <script type="text/javascript">
        location.href = '/cart/basket.php';
    </script>
<? endif ?>
<? foreach ($arResult['GRID']['ROWS'] as $key => $row): ?>
    <article class="sci-product<?= !$row['has_prod'] ? ' sci-product--off' : ''; ?>"
             data-id="<?= $row['ID'] ?>">
        <div class="sci-product__wrapper">
            <div class="sci-product__picture">
                <img src="<?= $row['PIC'] ?>" alt="">
            </div>
            <div class="sci-product__info">
                <div class="sci-product__sum-price">
                    <div class="product-price">
                        <? if ($row["DISCOUNT_PRICE_PERCENT"] > 0): ?>
                            <span class="product-price__value product-price__value--new">
              <?= $row['SUM'] ?> ₽
            </span>
                            <span class="product-price__value product-price__value--sale">
              <?= $row['SUM_FULL_PRICE_FORMATED'] ?> ₽
            </span>
                        <? else: ?>
                            <span class="product-price__value">
              <?= $row['SUM'] ?> ₽
            </span>
                        <? endif; ?>
                    </div>
                    <button
                            class="sci-product__delete sci-top__remove"
                            type="button" aria-label="Удалить товар"
                            data-id="<?= $row['ID'] ?>"
                    >
                    </button>
                </div>
                <a class="sci-product__name-link" href="<?= $row['DETAIL_PAGE_URL'] ?>">
                    <h3 class="sci-product__name">
                        <?= $row['NAME'] ?>
                    </h3>
                </a>
                <p class="sci-product__status">
                    <? if ($row['has_prod']) { ?>
                        Есть в наличии
                    <? } else { ?>
                        Товар закончился
                    <? } ?>
                </p>
                <div class="sci-product__counter-wrapper">
                    <div class="sci-product__counter">
                        <button
                                class="sci-top__count-down"
                                type="button"
                                aria-label="Уменьшить количество"
                                data-id="<?= $row['ID'] ?>" data-q="<?= $row['QUANTITY'] ?>"
                        >
                            <svg width="8" height="8">
                                <line x1="0" y1="4" x2="8" y2="4" stroke="#343434" stroke-width="1"/>
                            </svg>
                        </button>
                        <input
                                type="text"
                                readonly
                                value="<?= $row['QUANTITY'] ?>"
                                name="QUANTITY"
                        >
                        <button
                                class="sci-top__count-up"
                                type="button"
                                aria-label="Увеличить количество"
                                data-id="<?= $row['ID'] ?>" data-q="<?= $row['QUANTITY'] ?>"
                        >
                            <svg width="8" height="8">
                                <line x1="0" y1="4" x2="8" y2="4" stroke="#343434" stroke-width="1"/>
                                <line x1="4" y1="0" x2="4" y2="8" stroke="#343434" stroke-width="1"/>
                            </svg>
                        </button>
                    </div>
                    <span class="sci-product__price">
						<?= $row['PRICE_FORMATED'] ?> ₽
          </span>
                </div>
            </div>
        </div>
        <?
        $acessForThisElement = [];
        $dopProdForThisElement = [];
        foreach ($arResult['CML_PROD'] as $t => $el) {
            if ($el['OFFERS'][md5($row['PRODUCT_ID'])]) {
                $acessForThisElement = $el['ACESS_OBJ'];
                $dopProdForThisElement = $el['DOP_SERV_OBJ'];
            }
        }
        ?>
        <? if ($acessForThisElement or $dopProdForThisElement): ?>
            <div class="sci-add">
                <? if ($acessForThisElement) { ?>
                <div class="sci-add__block">
                    <h2 class="sci-add__title">С этим товаром покупают</h2>

                    <!-- При нажатии добавлять/удалять класс sci-add__button-hide--on, чтобы перевернуть стрелку -->
                    <button class="sci-add__button-hide" type="button" aria-label="Скрыть данные"></button>
                    <div class="sci-add__products">
                        <? foreach ($acessForThisElement as $r => $p) { ?>
                            <article class="sci-add__prod" data-id="<?= $p['id'] ?>">
                                <div class="sci-add__picture">
                                    <img src="<?= $p['img'] ?>" alt="">
                                </div>
                                <div class="sci-add__prices">
                                    <div class="sci-add__price">
                                        <span><?= number_format($p['price'], 0, '', ' ') ?></span> ₽
                                    </div>
                                </div>
                                <a class="sci-add__name-link" href="<?= $p['url'] ?>">
                                    <h3 class="sci-add__name">
                                        <?= $p['name'] ?>
                                    </h3>
                                </a>
                                <button
                                        class="sci-add__button addToCartBtn addToCartBtn_inCart"
                                        data-id="<?= $p['id'] ?>"
                                        type="button"
                                >
                                    В корзину
                                </button>
                            </article>
                        <? } ?>
                    </div>
                </div>
                <? } ?>
                <? if ($dopProdForThisElement) { ?>
                    <div class="sci-add__services sci-add__block">
                        <h2 class="sci-add__title">Дополнительные услуги</h2>
                        <button class="sci-add__button-hide" type="button" aria-label="Скрыть данные"></button>
                        <div class="sci-add__products">
                            <? foreach ($dopProdForThisElement as $r => $p) { ?>
                                <div class="sci-add__prod">
                                    <div class="sci-add__picture">
                                        <img src="<?= $p['img'] ?>" alt="">
                                    </div>
                                    <div class="sci-add__prices">
                                        <div class="sci-add__price">
                                            <span><?= number_format($p['price'], 0, '', ' ') ?></span> ₽
                                        </div>
                                    </div>
                                    <a href="<?= $p['url'] ?>">
                                        <h3 class="sci-add__name">
                                            <?= $p['name'] ?>
                                        </h3>
                                    </a>
                                    <button
                                            class="sci-add__button addToCartBtn addToCartBtn_inCart"
                                            data-id="<?= $p['id'] ?>"
                                            type="button"
                                    >
                                        В корзину
                                    </button>
                                </div>
                            <? } ?>
                        </div>
                    </div>
                <? } ?>
            </div>
        <? endif; ?>
    </article>
<? endforeach; ?>
<? if (!empty($arResult['GRID']['ROWS'])): ?>
    <button class="button-del button-del--bottom js-basket-clear" type="button">Очистить корзину</button>
<? endif; ?>
<? if ($_REQUEST['AJAX_CART'] == 'Y'): ?>
    <? die(); ?>
<? endif; ?>
