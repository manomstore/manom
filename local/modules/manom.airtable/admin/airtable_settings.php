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

$APPLICATION->SetTitle('Настройки');
$APPLICATION->SetAdditionalCSS($modulePath.'/admin/css/style.css');

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

$request = Application::getInstance()->getContext()->getRequest();

$defaultOptions = Option::getDefaults($moduleId);
$savedOptions = Option::getForModule($moduleId);

$options = array(
    'airtableId' => '',
    'apiKey' => '',
    'sections' => array(),
    'changeStatus' => '',
);

foreach ($options as $key => $value) {
    if (!empty($savedOptions[$key])) {
        if ($key === 'sections') {
            $savedOptions[$key] = explode('|', $savedOptions[$key]);
        }
        $options[$key] = $savedOptions[$key];
    } elseif (!empty($defaultOptions[$key])) {
        $options[$key] = $savedOptions[$key];
    }
}

if ($request->isPost() && check_bitrix_sessid()) {
    $post = $request->getPostList()->toArray();

    foreach ($post as $key => $value) {
        if ($key === 'airtableId' || $key === 'apiKey' || $key === 'changeStatus') {
            Option::set($moduleId, $key, $value);
        }

        if ($key === 'sections') {
            Option::set($moduleId, $key, implode('|', array_filter($value)));
        }
    }

    if (empty($post['changeStatus'])) {
        Option::set($moduleId, 'changeStatus', '');
    }

    LocalRedirect($APPLICATION->GetCurPage());
}
?>
<form action="<?=$APPLICATION->GetCurPage()?>" method="post">
    <?=bitrix_sessid_post()?>
    <div class="adm-detail-block">
        <div class="adm-detail-tabs-block"></div>
        <div class="adm-detail-content-wrap">
            <div class="adm-detail-content">
                <div class="adm-detail-title"></div>
                <div class="adm-detail-content-item-block">
                    <table class="adm-detail-content-table edit-table">
                        <tbody>
                            <tr>
                                <td class="adm-detail-content-cell-l">Airtable ID:</td>
                                <td class="adm-detail-content-cell-r">
                                    <input type="text" name="airtableId" value="<?=$options['airtableId']?>" size="30" maxlength="255">
                                </td>
                            </tr>
                            <tr>
                                <td class="adm-detail-content-cell-l">API Key:</td>
                                <td class="adm-detail-content-cell-r">
                                    <input type="text" name="apiKey" value="<?=$options['apiKey']?>" size="30" maxlength="255">
                                </td>
                            </tr>
                            <tr>
                                <td class="adm-detail-content-cell-l">Разделы:</td>
                                <td class="adm-detail-content-cell-r">
                                    <table>
                                        <tbody>
                                            <?php if (empty($options['sections'])): ?>
                                                <tr>
                                                    <td style="padding: 2px;">
                                                        <input type="text" name="sections[]" value="" size="30" maxlength="255">
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($options['sections'] as $section): ?>
                                                    <tr>
                                                        <td style="padding: 2px;">
                                                            <input
                                                                    type="text"
                                                                    name="sections[]"
                                                                    value="<?=$section?>"
                                                                    size="30"
                                                                    maxlength="255"
                                                            >
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                            <tr class="js-airtable-add-section-input-tr">
                                                <td>
                                                    <a href="#" class="js-airtable-add-section-input">Добавить</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td class="adm-detail-content-cell-l">Устанавливать статус "Выгружено в Битрикс":</td>
                                <td class="adm-detail-content-cell-r">
                                    <input
                                            type="checkbox"
                                            name="changeStatus"
                                            value="Y"
                                        <?=$options['changeStatus'] === 'Y' ? 'checked' : ''?>
                                    >
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="adm-detail-content-btns-wrap">
                <div class="adm-detail-content-btns">
                    <input type="submit" name="apply" value="Сохранить" class="adm-btn-save">
                </div>
            </div>
        </div>
    </div>
</form>