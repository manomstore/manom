<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

include $_SERVER["DOCUMENT_ROOT"] . $this->GetFolder() . "/section.php";
?>