<?php
global $USER;

if (!$USER->IsAuthorized()) {
    foreach ($arResult["DELIVERY"] as &$delivery) {
        $delivery["CHECKED"] = "N";
    }
}