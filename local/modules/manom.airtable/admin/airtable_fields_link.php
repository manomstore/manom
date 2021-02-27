<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;
use \Bitrix\Main\Application;
use \Manom\Airtable\Tools;
use \Manom\Airtable\FieldsMap;
use \Manom\Airtable\Bitrix\Property;

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

$APPLICATION->SetTitle('Привязка полей');
$APPLICATION->SetAdditionalCSS($modulePath.'/admin/css/style.css');

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';

$request = Application::getInstance()->getContext()->getRequest();

$fieldsMap = new FieldsMap();
$map = $fieldsMap->getMap();

$property = new Property(6);
$properties = $property->getAllPropertiesData();

if ($request->isPost() && check_bitrix_sessid()) {
    $post = $request->getPostList()->toArray();

    if (!empty($post['id']) && !empty($post['airtable']) && !empty($post['bitrix'])) {
        $data = array();
        foreach ($post['id'] as $id) {
            $data[] = array(
                'id' => $id,
                'airtable' => '',
                'bitrix' => '',
            );
        }

        foreach ($post['airtable'] as $key => $value) {
            $data[$key]['airtable'] = $value;
        }

        foreach ($post['bitrix'] as $key => $value) {
            $data[$key]['bitrix'] = $value;
        }

        $new = array();
        $update = array();
        foreach ($data as $item) {
            if (empty($item['id'])) {
                $new[] = $item;
            } else {
                foreach ($map['properties'] as $currentItem) {
                    if ((int)$item['id'] === (int)$currentItem['id']) {
                        if (
                            $item['airtable'] !== $currentItem['airtable'] ||
                            $item['bitrix'] !== $currentItem['bitrix']
                        ) {
                            $update[] = $item;
                        }

                        break;
                    }
                }
            }
        }

        foreach ($new as $item) {
            $fieldsMap->addLink($item);
        }

        foreach ($update as $item) {
            $fieldsMap->updateLink($item);
        }
    }

    LocalRedirect($APPLICATION->GetCurPage());
}
?>
<form
        name='airtable-link'
        action="<?=$APPLICATION->GetCurPage()?>"
        data-action="<?=$modulePath?>/admin/ajax/handler.php"
        method="post"
>
    <input type="hidden" name="link-properties" value='<?=json_encode(array_values($properties))?>'>
    <?=bitrix_sessid_post()?>
    <div class="adm-detail-block">
        <div class="adm-detail-tabs-block"></div>
        <div class="adm-detail-content-wrap">
            <div class="adm-detail-content">
                <div class="adm-detail-title"></div>
                <div class="adm-detail-content-item-block">
                    <table class="adm-detail-content-table edit-table">
                        <tbody>
                            <tr class="heading">
                                <td colspan="2">
                                    <b>Поля</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <table class="internal" width="1000">
                                        <tbody>
                                            <tr class="heading">
                                                <td align="center">Airtable</td>
                                                <td align="center">Bitrix</td>
                                            </tr>
                                            <?php foreach ($map['fields'] as $bitrix => $airtable): ?>
                                                <tr>
                                                    <td width="50%"><?=$airtable?></td>
                                                    <td width="50%"><?=$bitrix?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr>
                                                <td width="50%">Изображения</td>
                                                <td width="50%">PREVIEW_PICTURE</td>
                                            </tr>
                                            <tr>
                                                <td width="50%">Изображения</td>
                                                <td width="50%">DETAIL_PICTURE</td>
                                            </tr>
                                            <?php foreach ($map['product'] as $airtable => $bitrix): ?>
                                                <tr>
                                                    <td width="50%"><?= $airtable ?></td>
                                                    <td width="50%"><?= $bitrix ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr class="heading">
                                <td colspan="2">
                                    <b>Свойства</b>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    <table class="internal" width="1000">
                                        <tbody>
                                            <tr class="heading">
                                                <td align="center">Airtable</td>
                                                <td align="center">Bitrix</td>
                                                <td align="center"></td>
                                            </tr>
                                            <?php foreach ($map['properties'] as $item): ?>
                                                <?php
                                                $disabled = false;
                                                if (
                                                    $item['bitrix'] === 'MORE_PHOTO' ||
                                                    $item['bitrix'] === 'FEATURES2' ||
                                                    $item['bitrix'] === 'features' ||
                                                    $item['bitrix'] === 'contents_of_delivery'
                                                ) {
                                                    $disabled = true;
                                                }
                                                ?>
                                                <tr class="js-airtable-delete-link-tr">
                                                    <td width="45%">
                                                        <input
                                                                name="airtable[]"
                                                                type="text"
                                                                value="<?=$item['airtable']?>"
                                                                size="50"
                                                            <?=$disabled ? 'disabled' : ''?>
                                                        >
                                                    </td>
                                                    <td width="45%">
                                                        <select name="bitrix[]" <?=$disabled ? 'disabled' : ''?>>
                                                            <option value=""></option>
                                                            <?php foreach ($properties as $propertyItem): ?>
                                                                <option
                                                                        value="<?=$propertyItem['code']?>"
                                                                    <?=$propertyItem['code'] === $item['bitrix'] ? 'selected' : ''?>
                                                                >
                                                                    <?=$propertyItem['name']?>
                                                                    (<?=$propertyItem['code']?>)
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="id[]" value="<?=$item['id']?>">
                                                        <?php if ($disabled): ?>
                                                            <input type="hidden" name="airtable[]" value="<?=$item['airtable']?>">
                                                            <input type="hidden" name="bitrix[]" value="<?=$item['bitrix']?>">
                                                        <?php else: ?>
                                                            <button class="js-airtable-delete-link" data-id="<?=$item['id']?>">
                                                                Удалить
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            <tr class="js-airtable-add-link-tr">
                                                <td colspan="3">
                                                    <a href="#" class="js-airtable-add-link">Добавить</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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