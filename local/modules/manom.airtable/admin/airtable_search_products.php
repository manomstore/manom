<?php

use Bitrix\Main\Context;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\AdminPageNavigation;
use \Manom\Airtable\Tools;
use Manom\Content\Section;

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

$APPLICATION->SetTitle('Поиск товаров');
$APPLICATION->SetAdditionalCSS($modulePath . '/admin/css/style.css');

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php';

$request = Context::getCurrent()->getRequest();
$propertyValue = $propertyCode = "";
$rsData = null;
$isUpdateList = $request->get("mode") && $request->get("table_id");

if ($request->get("apply")) {
    $propertyCode = trim($request->get("property_code"));
    $propertyValue = $request->get("property_value");

    if ($propertyCode) {
        $arFilter = [
            "IBLOCK_ID" => \Helper::CATALOG_IB_ID,
        ];
        $propertyValueField = $propertyValue ? $propertyValue : false;
        $propertyCodeField = "PROPERTY_" . $propertyCode;

        $propertyData = \CIBlockProperty::GetList(
            [],
            [
                'IBLOCK_ID' => \Helper::CATALOG_IB_ID,
                'CODE'      => $propertyCode,
            ]
        )->GetNext();

        $isListType = $propertyData["PROPERTY_TYPE"] === "L";

        if ($propertyValueField === false) {
            $propertyCodeField = "!" . $propertyCodeField;
        }

        $propertyCodeField .= $isListType ? "_VALUE" : "";

        $arFilter[$propertyCodeField] = $propertyValueField;

        $arSelect = [
            "ID",
            "IBLOCK_ID",
            "IBLOCK_TYPE_ID",
            "NAME",
            "XML_ID",
            "IBLOCK_SECTION_ID",
            "PROPERTY_" . $propertyCode,
        ];

        $resultIds = [];

        if ($propertyData !== false) {
            $result = CIBlockElement::GetList([$by => $order], $arFilter, false, false, $arSelect);
            while ($row = $result->GetNext()) {
                if ($propertyValueField && $row[strtoupper("PROPERTY_" . $propertyCode . "_VALUE")] !== $propertyValueField) {
                    continue;
                }
                $resultIds[] = $row["ID"];
            }
        }

        if (!empty($resultIds)) {
            $rsData = CIBlockElement::GetList([$by => $order], ["ID" => $resultIds,], false, false, $arSelect);
        } else {
            //Для вывода пустого списка, если в предыдущем запросе не находим эквивалентных значений
            $rsData = new \CDBResult();
        }
    } else {
        \CAdminMessage::ShowMessage("Код свойства не указан");
    }
    $cdbRes = new CDBResult();
}
?>
<form action="<?= $APPLICATION->GetCurPageParam() ?>" method="get">
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
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
                                <span class="adm-required-field">Код свойства:</span>
                            </td>
                            <td class="adm-detail-content-cell-r">
                                <input type="text" name="property_code" value="<?= $propertyCode ?>" size="30"
                                       maxlength="255">
                            </td>
                        </tr>
                        <tr>
                            <td class="adm-detail-content-cell-l">Значение свойства:</td>
                            <td class="adm-detail-content-cell-r">
                                <input type="text" name="property_value" value="<?= $propertyValue ?>" size="30"
                                       maxlength="255">
                            </td>
                        </tr>
                        <tr>
                            <td class="adm-detail-content-cell-l"></td>
                            <td class="adm-detail-content-cell-r">
                                <input type="submit" name="apply" value="Найти" class="adm-btn-save">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <?


                    if (isset($rsData)) {
                        $sTableID = "airtable_search_products";
                        $oSort = new CAdminSorting($sTableID, "ID", "desc");
                        $lAdmin = new CAdminList($sTableID, $oSort);

                        $lAdmin->AddHeaders(
                            [
                                [
                                    "id"      => "ID",
                                    "content" => "ID",
                                    "default" => true,
                                    "sort"    => "id"
                                ],
                                [
                                    "id"      => "NAME",
                                    "content" => "Название",
                                    "default" => true,
                                    "sort"    => "name"
                                ],
                                [
                                    "id"      => "IBLOCK_SECTION_ID",
                                    "content" => "Раздел",
                                    "default" => true,
                                    "sort"    => "iblock_section_id"
                                ],
                                [
                                    "id"      => "XML_ID",
                                    "content" => "Внешний код",
                                    "default" => true,
                                    "sort"    => "xml_id"
                                ],
                            ]
                        );

                        $rsData = new CAdminResult($rsData, $sTableID);
                        $rsData->NavStart(10);
                        $lAdmin->NavText($rsData->GetNavPrint("Товары"));
                        $obSection = new Section();
                        while ($arRes = $rsData->NavNext(true, "f_")) {
                            $section = $obSection->getFirst(["id" => $f_IBLOCK_SECTION_ID]);
                            $parentSection = [];
                            if ($section["parentId"]) {
                                $parentSection = $obSection->getFirst(["id" => $section["parentId"]]);
                            }

                            $row =& $lAdmin->AddRow($f_ID, $arRes);
                            $sectionLink = "iblock_section_edit.php?IBLOCK_ID=" . $f_IBLOCK_ID . "&type="
                                . $f_IBLOCK_TYPE_ID . "&ID=" . $section["id"] . "&lang=" . LANG;
                            $sectionName = $section["name"];

                            if (!empty($parentSection)) {
                                $sectionName = trim($parentSection["name"]) . "/" . trim($sectionName);
                            }

                            $link = "iblock_element_edit.php?IBLOCK_ID=" . $f_IBLOCK_ID . "&type="
                                . $f_IBLOCK_TYPE_ID . "&ID=" . $f_ID . "&lang=" . LANG;
                            $row->AddViewField("ID", '<a href="' . $link . '">' . $f_ID . '</a>');
                            $row->AddViewField("NAME", $f_NAME);
                            $row->AddViewField("IBLOCK_SECTION_ID", '<a href="' . $sectionLink . '">' . $sectionName . '</a>');
                            $row->AddViewField("XML_ID", $f_XML_ID);
                            $arActions = [];
                            $arActions[] = [
                                "ICON"    => "edit",
                                "DEFAULT" => true,
                                "TEXT"    => "Открыть",
                                "ACTION"  => $lAdmin->ActionRedirect($link)
                            ];
                            $row->AddActions($arActions);
                        }
                        $lAdmin->AddFooter(
                            [
                                ["title" => "Товаров всего", "value" => $rsData->SelectedRowsCount()],
                            ]
                        );
                        if ($isUpdateList) {
                            $APPLICATION->RestartBuffer();
                        }
                        $lAdmin->CheckListMode();
                        require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

                        $lAdmin->DisplayList();
                        if ($isUpdateList) {
                            die();
                        }
                    }
                    ?>

                </div>
            </div>
            <div class="adm-detail-content-btns-wrap">
                <div class="adm-detail-content-btns">
                </div>
            </div>
        </div>
    </div>
</form>