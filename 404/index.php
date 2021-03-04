<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("404");
?>
<div class="content not-found">
    <div class="container">
      <div class="not-found__cover">
        <img class="not-found__image" src="./../local/templates/manom/assets/img/404.svg" >
        <h2 class="not-found__title"><span>Хм, это сбивает с толку.</span> <span>Куда делась страница?!</span></h2>
        <p class="not-found__text">Вы можете перейти на <a href="">главную страницу</a><br> или воспользоваться <a href="">каталогом товаров.</a></p>
      </div>
    </div>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>

