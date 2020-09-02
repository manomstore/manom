<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("нет в наличии");
?>
<div class="content">
    <div class="container  empty-container">
        <div class="empty">
            <div class="empty__block empty__block--brand">
              <img src="./../local/templates/manom/assets/img/honor.svg" >
              <p class="empty__text">
                Здесь пока пусто. Посмотрите другие <a href="#">смартфоны</a>
              </p>
            </div>
            <!-- <div class="empty__block empty__block--goods">
              <h2>
                Пылесосы
              </h2>
              <p class="empty__text">
                Здесь пока пусто. Посмотрите другие товары в разделе <a href="#">техника для дома</a>
              </p>
            </div> -->
        </div>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>