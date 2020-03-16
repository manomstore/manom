<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;
use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;
use \Manom\Airtable\Tools;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';

$modulePermissions = $APPLICATION::GetGroupRight('main');
if ($modulePermissions < 'W') {
    $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

Loader::includeModule('manom.airtable');

$tools = new Tools;
$moduleId = $tools->getModuleId();
$modulePath = $tools->getModulePath(false);

Asset::getInstance()->addJs($modulePath.'/admin/js/jquery-3-4-1.min.js');
Asset::getInstance()->addJs($modulePath.'/admin/js/script.js');

$APPLICATION->SetTitle('Импорт');
$APPLICATION->SetAdditionalCSS($modulePath.'/admin/css/style.css');

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

$request = Application::getInstance()->getContext()->getRequest();

$sections = $tools->getSections();
?>
<form name="airtable-import" action="<?=$modulePath?>/admin/ajax/handler.php" method="post">
    <?=bitrix_sessid_post()?>
    <div class="adm-detail-block">
        <div class="adm-detail-tabs-block"></div>
        <div class="adm-detail-content-wrap">
            <div class="adm-detail-content">
                <div class="airtable-import-info js-airtable-import-info">
                    <p>Выгрузка запущена, не закрывайте вкладку до окончания процесса.</p>
                </div>
                <div class="airtable-import-success js-airtable-import-success">
                    <p>Выгрузка успешно выполнена.</p>
                </div>
                <div class="airtable-import-error js-airtable-import-error">
                    <p>Не удалось выполнить выгрузку. Errors:</p>
                    <div class="js-airtable-import-errors"></div>
                </div>
                <div class="adm-detail-title"></div>
                <div class="adm-detail-content-item-block">
                    <table class="adm-detail-content-table edit-table">
                        <tbody>
                            <?php if (!empty($sections)): ?>
                                <tr class="heading">
                                    <td colspan="2">
                                        <b>Полная выгрузка</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="adm-detail-content-cell-l" width="50%"></td>
                                    <td class="adm-detail-content-cell-r" width="50%">
                                        <input type="submit" name="submit" value="Запустить" class="js-airtable-import-all">
                                    </td>
                                </tr>
                                <tr class="heading">
                                    <td colspan="2">
                                        <b>Выгрузка разделов</b>
                                    </td>
                                </tr>
                                <?php foreach ($sections as $section): ?>
                                    <tr>
                                        <td class="adm-detail-content-cell-l"><?=$section?>:</td>
                                        <td class="adm-detail-content-cell-r">
                                            <input type="checkbox" name="sections" value="<?=$section?>">
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td class="adm-detail-content-cell-l"></td>
                                    <td class="adm-detail-content-cell-r">
                                        <input type="submit" name="submit" value="Запустить" class="js-airtable-import-sections">
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php /*
                            <tr class="heading">
                                <td colspan="2">
                                    <b>Выгрузка элементов</b>
                                </td>
                            </tr>
                            <td class="adm-detail-content-cell-l">Внешний код:</td>
                            <td class="adm-detail-content-cell-r">
                                <input type="text" name="xmlId" value="" size="30" maxlength="255">
                            </td>
                            <tr>
                                <td class="adm-detail-content-cell-l"></td>
                                <td class="adm-detail-content-cell-r">
                                    <input type="submit" name="submit" value="Запустить" class="js-airtable-import-element">
                                </td>
                            </tr>
                            */ ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="adm-detail-content-btns-wrap">
                <div class="adm-detail-content-btns"></div>
            </div>
        </div>
    </div>
</form>