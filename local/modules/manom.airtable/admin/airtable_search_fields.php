<?php

use Bitrix\Main\Context;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;
use Manom\Airtable\Import;
use \Manom\Airtable\Tools;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php';

$modulePermissions = $APPLICATION::GetGroupRight('main');
if ($modulePermissions < 'W') {
    $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

Loader::includeModule('manom.airtable');

$tools = new Tools;
$modulePath = $tools->getModulePath(false);

Asset::getInstance()->addJs($modulePath . '/admin/js/jquery-3-4-1.min.js');
Asset::getInstance()->addJs($modulePath . '/admin/js/script.js');

$APPLICATION->SetTitle('Поиск полей в Airtable');
$APPLICATION->SetAdditionalCSS($modulePath . '/admin/css/style.css');

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

$request = Context::getCurrent()->getRequest();
$fieldName = "";
$foundSections = [];
$existError = false;

if ($request->get("apply")) {
    $fieldName = trim($request->get("field_name"));
    try {
        $import = new Import();
        $foundSections = $import->getSectionsForField($fieldName);
    } catch (\Exception $e) {
        \CAdminMessage::ShowMessage("Ошибка поиска.");
        \Manom\Tools::errorToLog($e, "at_api", $fieldName);
        $existError = true;
    }
}
?>
<script>
    var submitHandler = function (form) {
        debugger;
        BX.showWait();
        form.querySelector("input[type='submit']").disabled = true;
        form.submit();
    }
</script>
<form action="<?= $APPLICATION->GetCurPageParam() ?>" method="get" onsubmit="submitHandler(this)">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="hidden" name="apply" value="Y">
    <div class="adm-detail-block">
        <div class="adm-detail-tabs-block"></div>
        <div class="adm-detail-content-wrap">
            <div class="adm-detail-content">
                <div class="adm-detail-title"></div>
                <div class="adm-detail-content-item-block">
                    <table class="adm-detail-content-table edit-table" style="margin-bottom: 2%;">
                        <tbody>
                        <tr>
                            <td class="adm-detail-content-cell-l">
                                <span class="adm-required-field">Название поля:</span>
                            </td>
                            <td class="adm-detail-content-cell-r">
                                <input type="text" name="field_name" value="<?= $fieldName ?>" size="30"
                                       maxlength="255" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="adm-detail-content-cell-l"></td>
                            <td class="adm-detail-content-cell-r">
                                <input type="submit" value="Найти" class="adm-btn-save">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <? if ($request->get("apply") && !$existError): ?>
                        <div style="padding-left: 30%;">
                            <? if (!empty($foundSections)): ?>
                                <p>Поле "<?= $fieldName ?>" найдено в разделах:</p>
                                <? foreach ($foundSections as $section): ?>
                                    <p>- <?= $section ?></p>
                                <? endforeach; ?>
                            <? else: ?>
                                <p>Поле "<?= $fieldName ?>" не найдено ни в одном разделе</p>
                            <? endif; ?>
                        </div>
                    <? endif; ?>
                </div>
            </div>
            <div class="adm-detail-content-btns-wrap">
                <div class="adm-detail-content-btns">
                </div>
            </div>
        </div>
    </div>
</form>