<?php

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/manom.airtable/')) {
    $path = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/manom.airtable/admin/airtable_sections.php';
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/local/modules/manom.airtable/')) {
    $path = $_SERVER['DOCUMENT_ROOT'].'/local/modules/manom.airtable/admin/airtable_sections.php';
}

require_once($path);
