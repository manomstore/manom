<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
global $USER;
?>
<div class="content">
  <main class="shopcart container" id="so_main_block" style="position:relative;">
    <div class="preloaderCatalog preloaderCatalogActive">
      <div class="windows8">
        <div class="wBall" id="wBall_1">
          <div class="wInnerBall">
          </div>
        </div>
        <div class="wBall" id="wBall_2">
          <div class="wInnerBall">
          </div>
        </div>
        <div class="wBall" id="wBall_3">
          <div class="wInnerBall">
          </div>
        </div>
        <div class="wBall" id="wBall_4">
          <div class="wInnerBall">
          </div>
        </div>
        <div class="wBall" id="wBall_5">
          <div class="wInnerBall">
          </div>
        </div>
      </div>
    </div>
    <div class="shopcart__wrapper">
      <h1 class="shopcart__title">Корзина</h1>
    </div>
    <div class="shopcart-main">
      <div class="sci-product">
        <div class="notetext">
          Ваша корзина пуста.
        </div>
      </div>
    </div>
  </main>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
