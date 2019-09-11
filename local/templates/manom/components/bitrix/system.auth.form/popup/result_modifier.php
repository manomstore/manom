<?php
$arResult['SHOW_ERRORS'] = \Bitrix\Main\Context::getCurrent()
    ->getRequest()
    ->get("TYPE") === "SEND_PWD" ? "N" : $arResult['SHOW_ERRORS'];