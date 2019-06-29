<?//
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//$APPLICATION->SetTitle("Профиль");
// if ($USER->IsAuthorized() and $_REQUEST['prod']){?>
<!--    <div class="personal container" style="position: relative;">-->
<!--			<div class="preloaderCatalog">-->
<!--				<div class="windows8">-->
<!--					<div class="wBall" id="wBall_1">-->
<!--						<div class="wInnerBall"></div>-->
<!--					</div>-->
<!--					<div class="wBall" id="wBall_2">-->
<!--						<div class="wInnerBall"></div>-->
<!--					</div>-->
<!--					<div class="wBall" id="wBall_3">-->
<!--						<div class="wInnerBall"></div>-->
<!--					</div>-->
<!--					<div class="wBall" id="wBall_4">-->
<!--						<div class="wInnerBall"></div>-->
<!--					</div>-->
<!--					<div class="wBall" id="wBall_5">-->
<!--						<div class="wInnerBall"></div>-->
<!--					</div>-->
<!--				</div>-->
<!--			</div>-->
<!--        <div class="personal-main">-->
<!--            <aside class="personal__aside">-->
<!--                <h1 class="personal__title">Личный кабинет</h1>-->
<!--                --><?//if ($USER->IsAuthorized()){?>
<!--                  <p id="personal-nav__item1" class="personal-nav__item personal-nav__name" data-id="pb-info">Мои настройки</p>-->
<!--                  <p class="personal-nav__name">Покупки:</p>-->
<!--                  <a href="/user/history.php" id="personal-nav__item2" class="personal-nav__item">История покупок</a>-->
<!--                  <a href="/user/favorite/" id="personal-nav__item4" class="personal-nav__item">Товары в избранном</a>-->
<!--                  <a href="/catalog/compare/" id="personal-nav__item4" class="personal-nav__item">Сравнение товаров</a>-->
<!--                  <p class="personal-nav__name">Моя активность:</p>-->
<!--                  <a href="/user/review/add-list/" id="personal-nav__item4" class="personal-nav__item">Добавить отзыв товару</a>-->
<!--                  <a href="/user/review/" id="personal-nav__item4" class="personal-nav__item">Мои отзывы</a>-->
<!--                --><?//}else{?>
<!--                  <a href="/auth/" class="personal-nav__item">Авторизация</a>-->
<!--                --><?//}?>
<!--            </aside>-->
<!--            <main class="personal-block">-->
<!--              --><?//
//              $prodID = getAllProdsWithoutReviewFromOrders();
//              $has = false;
//              foreach ($prodID as $key => $val) {
//                if ($val == $_REQUEST['prod']) {
//                  $has = true;
//                }
//              }
//              if (!$has) {
//                LocalRedirect("/");
//              }
//              if ($_POST['prodID'] and $_POST['review_rating'] and $_POST['review_merits'] and $_POST['review_disadvantages'] and $_POST['review_comments']) {
//                $el = new CIBlockElement;
//                $arFields = array(
//                  'NAME' => "Отзыв",
//                  'IBLOCK_ID' => 11,
//                  'PREVIEW_TEXT' => $_POST['review_comments'],
//                  'PROPERTY_VALUES' => array(
//                    'RV_MERITS' => $_POST['review_merits'],
//                    'RV_DISADVANTAGES' => $_POST['review_disadvantages'],
//                    'RV_PRODCTS' => $_REQUEST['prod'],
//                    'RV_RATING' => $_POST['review_rating'],
//                    'RV_USER' => $USER->GetID(),
//                  )
//                );
//                // print_r($arFields);
//                $el->Add($arFields);
//                LocalRedirect("/user/review/");
//              }
//              ?>
<!--              <section class="catalog-block">-->
<!--                <h2 class="pb-info__title">Добавление отзыва:</h2>-->
<!--                <form action="" method="post">-->
<!--                  <input type="hidden" name="prodID" value="--><?//=$_REQUEST['prod']?><!--">-->
<!--                  <label for="review_rating" class="sci-contact__l-label w-100">Оценка*:-->
<!--                    <select name="review_rating" id="review_rating" class="sci-contact__input" required>-->
<!--                      <option selected="" value="1">1</option>-->
<!--                      <option value="2">2</option>-->
<!--                      <option value="3">3</option>-->
<!--                      <option value="4">4</option>-->
<!--                      <option value="5">5</option>-->
<!--                    </select>-->
<!--                  </label>-->
<!--                  <label for="review_merits" class="sci-contact__l-label w-100">Достоинства*:-->
<!--                    <textarea name="review_merits" id="review_merits" class="sci-contact__input w-100" placeholder="Опишите достоинства" rows="4" style="margin: 10px 0 15px 0;" required></textarea>-->
<!--                  </label>-->
<!--                  <label for="review_disadvantages" class="sci-contact__l-label w-100">Недостатки*:-->
<!--                    <textarea name="review_disadvantages" id="review_disadvantages" class="sci-contact__input w-100" placeholder="Опишите недостатки" rows="4" style="margin: 10px 0 15px 0;" required></textarea>-->
<!--                  </label>-->
<!--                  <label for="review_comments" class="sci-contact__l-label w-100">Комментарий*:-->
<!--                    <textarea name="review_comments" id="review_comments" class="sci-contact__input w-100" placeholder="Оставте коментарий" rows="4" style="margin: 10px 0 15px 0;" required></textarea>-->
<!--                  </label>-->
<!--                  <button type="submit" name="button" class="shopcart-sidebar__button" style="border: none;">Отправить</button>-->
<!--                </form>-->
<!--              </section>-->
<!--            </main>-->
<!--        </div>-->
<!---->
<!--    </div>-->
<?//}else{
//    LocalRedirect("/");
//}?>
<?//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
