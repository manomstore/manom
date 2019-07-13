<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
global $USER;
?>
  <main class="shopcart" id="so_main_block">
    <div class="container">
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
      <h1 class="shopcart__title">Корзина</h1>
      <div class="notetext ">
        Ваша корзина пуста.
      </div>
    </div>
  </main><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>