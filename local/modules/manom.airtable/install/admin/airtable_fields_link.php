<?php

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/manom.airtable/')) {
    $path = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/manom.airtable/admin/airtable_fields_link.php';
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/local/modules/manom.airtable/')) {
    $path = $_SERVER['DOCUMENT_ROOT'].'/local/modules/manom.airtable/admin/airtable_fields_link.php';
}

require_once($path);
