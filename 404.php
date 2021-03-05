<?
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404", "Y");

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("404");
?>
<div class="content not-found">
    <div class="container">
        <div class="not-found__cover">
            <img class="not-found__image" src="<?= SITE_TEMPLATE_PATH ?>/assets/img/404.svg">
            <h2 class="not-found__title"><span>Хм, это сбивает с толку.</span> <span>Куда делась страница?!</span></h2>
            <p class="not-found__text">Вы можете перейти на <a href="<?= SITE_DIR ?>">главную страницу</a><br>
                или воспользоваться <a href="<?= SITE_DIR ?>catalog/">каталогом товаров.</a></p>
        </div>
    </div>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>

