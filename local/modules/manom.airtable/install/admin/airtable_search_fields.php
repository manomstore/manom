<?php

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/manom.airtable/')) {
    $path = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/manom.airtable/admin/airtable_search_fields.php';
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/local/modules/manom.airtable/')) {
    $path = $_SERVER['DOCUMENT_ROOT'].'/local/modules/manom.airtable/admin/airtable_search_fields.php';
}

require_once($path);
