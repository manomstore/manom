<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use \Bitrix\Main\Localization\Loc;
use \Hozberg\Characteristics;

$module_id = "hozberg.characteristics";

global $USER_FIELD_MANAGER, $APPLICATION;

if (!\Bitrix\Main\Loader::includeModule($module_id)) {
    return;
}

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/options.php");
Loc::loadMessages(__FILE__);


$MODULE_RIGHTS = $APPLICATION->GetGroupRight($module_id);


if ($MODULE_RIGHTS === "D") {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

if ($request->isPost() && !empty($request->getPost("save")) && check_bitrix_sessid() && $MODULE_RIGHTS >= "S") {
    if (is_array($request->getPost("SHOW_PROPERTIES"))) {
        $propertyIds = array_values((array)$request->getPost("SHOW_PROPERTIES"));
    } else {
        $propertyIds = [];
    }

    $successSave = Characteristics::updateShowProperties($propertyIds);

    if ($successSave) {
        CAdminMessage::ShowMessage(
            [
                'DETAILS' => Loc::getMessage("HOZBERG_CHARACTERISTICS_ADMIN_DATA_SAVED"),
                'HTML' => true,
                'TYPE' => 'OK',
            ]
        );
    } else {
        CAdminMessage::ShowMessage(
            [
                "TYPE" => "ERROR",
                "MESSAGE" => Loc::getMessage("HOZBERG_CHARACTERISTICS_ADMIN_DATA_ERROR"),
                "DETAILS" => "",
                "HTML" => true,
            ]
        );
    }
}

$showCharacteristics = Characteristics::getShowCharacteristics();

$aTabs = [
    [
        "DIV" => "characteristics",
        "TAB" => Loc::getMessage("HOZBERG_CHARACTERISTICS_PAGE_TITLE"),
        "TITLE" => Loc::getMessage("HOZBERG_CHARACTERISTICS_PAGE_TITLE"),
    ],
];

$tabControl = new CAdminForm("HOZBERG_CHARACTERISTICS_SETTINGS_tabControl", $aTabs);

//if ( $MODULE_RIGHTS < "S" ) {
//	$tabControl->SetShowSettings(false);
//}

$tabControl->SetShowSettings(false);


$tabControl->BeginEpilogContent();
echo bitrix_sessid_post();
$tabControl->EndEpilogContent();

$tabControl->Begin();
$tabControl->BeginNextFormTab();

$arUserFields = [
    "n1" => [

    ],
    "n2" => [

    ],
    "n3" => [

    ],
];

$properties = \Bitrix\Iblock\PropertyTable::getList(
    [
        "filter" => [
            "IBLOCK_ID" => 6,
            "ACTIVE" => "Y"
        ]
    ]
)->fetchAll();

$tabControl->BeginCustomField($arUserField["FIELD_NAME"], preg_replace("/^UF_/", "", $arUserField["FIELD_NAME"]));
?>
    <style>
        .hozberg_characteristics-head-col {
            text-align: left;
            width: 3%;
        }

        .hozberg_characteristics-col-l {
            text-align: center;
        }

        .hozberg_characteristics-col-r {
            width: 10%;
        }

        .hozberg_characteristics-checked-row {
            background-color: lightgrey;
        }
    </style>
<?
if (!empty($properties)): ?>
    <tr>
        <th class="hozberg_characteristics-head-col">
            <?= Loc::getMessage("HOZBERG_CHARACTERISTICS_ADMIN_PRODUCT_PROPERTY_COL") ?>
        </th>
        <th class="hozberg_characteristics-head-col">
            <?= Loc::getMessage("HOZBERG_CHARACTERISTICS_ADMIN_FLAG_COL") ?>
        </th>
    </tr>
    <?
    foreach ($properties as $property):
        $checked = in_array($property["ID"], $showCharacteristics);
        ?>
        <tr class="<?= $checked ? "hozberg_characteristics-checked-row" : "" ?>">
            <td class="hozberg_characteristics-col-l">
                <label for="<?= $property["CODE"] ?>"><?= $property["NAME"] ?> [<?= $property["CODE"] ?>] [<?=$property["ID"]?>]</label>
            </td>
            <td class="hozberg_characteristics-col-r">
                <input type="checkbox"
                    <?= $checked ? "checked='checked'" : "" ?>
                       name="SHOW_PROPERTIES[<?= $property["CODE"] ?>]"
                       id="<?= $property["CODE"] ?>"
                       value="<?= $property["ID"] ?>"
                >
            </td>
        </tr>
    <?endforeach;
else:?>
    <tr>
        <th class="hozberg_characteristics-head-col">
            <?= Loc::getMessage("HOZBERG_CHARACTERISTICS_ADMIN_PROPERTY_LIST_EMPTY") ?>
        </th>
    </tr>
<?endif;
$tabControl->EndCustomField($arUserField["FIELD_NAME"]);

if (!empty($properties)) {
    $tabControl->Buttons(
        [
            'btnApply' => false,
            'disabled' => $MODULE_RIGHTS < "S",
            "back_url" => $APPLICATION->GetCurPageParam(),
        ]
    );
}

$tabControl->Show();
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
