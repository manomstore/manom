<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
?>
<section id="pb-comments" class="personal-block__section" style="display: flex;">
  <h2 class="pb-comments__title">Мои отзывы:</h2>
  <!-- 	block -->

  <?foreach ($arResult['ITEMS'] as $key => $value) {?>
    <?$rev = getUserReviewForProd($value['ID']);?>
    <div class="pb-comments__block col-12">
        <div class="pb-comments__product">
            <div class="product-card">
                <div class="product-card__img">
                    <?foreach ($value['OFFERS'][0]['PROPERTIES']['MORE_PHOTO']['VALUE'] as $val => $img ) {
                        $file = CFile::ResizeImageGet($img, array('width'=>350, 'height'=>350), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
                        <div class="product-card__slide">
                            <img src="<?=$file['src']?>" alt="">
                        </div>
                    <?}?>
                </div>
                <p class="p-label-top active">
                    <?if ($value['OFFERS'][0]['PROPERTIES']['PRODUCT_OF_THE_DAY']['VALUE'] == 'Да') {?>
                    Товар дня
                    <?}?>
                  </p>
                <div class="p-nav-top">
                    <label>
                        <input class="p-nav-top__checkbox" type="checkbox" <?=checkProdInFavoriteAndCompareList($value['OFFERS'][0]['ID'], 'UF_FAVORITE_ID') ? 'checked' : '';?>>
                        <div class="p-nav-top__favorite addToFavoriteList <?=!checkProdInFavoriteAndCompareList($value['OFFERS'][0]['ID'], 'UF_FAVORITE_ID') ? 'notActive' : '';?>" data-id='<?=$value['OFFERS'][0]['ID']?>' title="в избранное"></div>
                    </label>
                    <div class="p-nav-top__list addToCompareList <?=!checkProdInFavoriteAndCompareList($value['OFFERS'][0]['ID'], 'UF_COMPARE_ID') ? 'notActive' : '';?>" data-id='<?=$value['OFFERS'][0]['ID']?>'></div>
                </div>
                <h3 class="p-name" style="text-align:center;">
                    <a href="<?=$value['DETAIL_PAGE_URL']?>"><?=$value['NAME']?></a>
                </h3>
            </div>
        </div>
        <div class="pb-comments__content">
            <div class="pb-comments__header">
              <?
              $getUsersByID = CUser::GetList(
                ($by='ID'),
                ($order='ASC'),
                array(
                  'ID' => $USER->GetID()
                ),
                array(
                  'FIELDS' => array(
                    'NAME',
                    'LAST_NAME',
                    'ID'
                  )
                )
              );
              if ($resUser = $getUsersByID->Fetch()) {
              ?>
                <p class="pb-comments__text">Автор: <span><?=$resUser['NAME'].' '.$resUser['LAST_NAME']?></span></p>
              <?}?>
              <p class="pb-comments__text">Дата: <span><?=$rev['date']?></span></p>
            </div>
            <?if($rev['merits']):?>
              <p class="pb-comments__chapter">Достоинства:</p>
              <p class="pb-comments__text"><?=$rev['merits']?></p>
            <?endif;?>
            <?if($rev['disadvantages']):?>
              <p class="pb-comments__chapter">Недостатки:</p>
              <p class="pb-comments__text"><?=$rev['disadvantages']?></p>
            <?endif;?>
            <?if($rev['comment']):?>
              <p class="pb-comments__chapter">Комментарий:</p>
              <p class="pb-comments__text"><?=$rev['comment']?></p>
            <?endif;?>
          <p class="pb-comments__text" style="opacity:0;">Хорошая звукоизоляция: чтобы слушать музыку, прогуливаясь по шумной улице, не нужно задирать громкость на полную.</p>
        </div>
    </div>
  <?}?>
</section>
