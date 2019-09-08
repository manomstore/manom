<?php
if (isset($APPLICATION->arAuthResult) && $APPLICATION->arAuthResult !== true) {
    $arResult['ERROR_MESSAGE'] = $APPLICATION->arAuthResult;
}
