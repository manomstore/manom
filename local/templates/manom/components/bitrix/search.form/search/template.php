<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true); ?>

<form action="<?= $arResult["FORM_ACTION"] ?>" is-search-form class="js-search-field">
  <div class="popup-block__field popup-block__field--row">
    <div class="popup-block__input-wrapper">
      <? $APPLICATION->IncludeComponent(
        "bitrix:search.suggest.input",
        "header-search",
        [
          "NAME"          => "q",
          "VALUE"         => "",
          "INPUT_SIZE"    => 15,
          "DROPDOWN_SIZE" => 10,
        ],
        $component,
        ["HIDE_ICONS" => "Y"]
      ); ?>
      <i class="search-block__submit"></i>
      <button class="popup-block__input-clear" type="button" aria-label="Очистить поле поиска"></button>
    </div>
    <button class="search-block__submit-button">Найти</button>
  </div>

  <ul class="search-block__list">
    <li>
      <a class="search-block__mark" href="#">airpods</a>
    </li>
    <li>
      <a class="search-block__mark" href="#">iphone x</a>
    </li>
    <li>
      <a class="search-block__mark" href="#">airpods</a>
    </li>
  </ul>
</form>