<?

namespace Hozberg;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Entity\DataManager;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

class Characteristics
{
    const MODULE_ID = "hozberg.characteristics";
    public static $LAST_ERROR = "";

    public static function HandlerOnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
    {
        foreach ($aModuleMenu as $key1 => $value) {
            if ($value["module_id"] == "catalog" || $value["items_id"] == "menu_catalog_list") {
                $newMenu = array(
                    "text" => Loc::getMessage("HOZBERG_CHARACTERISTICS_MENU_TEXT"),
                    "title" => Loc::getMessage("HOZBERG_CHARACTERISTICS_TITLE"),
                    "url" => "hozberg_characteristics.php?lang=" . LANGUAGE_ID,
                    "more_url" => array(),
                );
                $aModuleMenu[$key1]["items"][] = $newMenu;
                break;
            }
        }
    }

    public static function HandlerOnAfterIBlockPropertyDelete($property)
    {
        if ((int)$property["IBLOCK_ID"] !== 6) {
            return;
        }

        self::clearProperty($property["ID"]);
    }

    public static function updateShowProperties($propertyIds)
    {
        $existError = false;
        try {
            /** @var DataManager $entityDataClass */
            $entityDataClass = self::getEntityDataClass(self::getHLBlockId());
            $currentShowProperties = $entityDataClass::getList()->fetchAll();

            foreach ($currentShowProperties as &$currentShowProperty){
                if (!in_array($currentShowProperty["UF_PROPERTY_ID"],$propertyIds)){
                    $entityDataClass::delete($currentShowProperty["ID"]);
                    $currentShowProperty = null;
                }
            }
            unset($currentShowProperty);

            $currentShowProperties = array_values(array_filter($currentShowProperties));

            $currentShowProperties = array_map(
                function ($item) {
                    return (int)$item["UF_PROPERTY_ID"];
                },
                $currentShowProperties
            );

            foreach ($propertyIds as $propertyId) {
                if ((int)$propertyId <= 0) {
                    continue;
                }

                if (!in_array($propertyId, $currentShowProperties)) {
                    $resultAdd = $entityDataClass::add(
                        [
                            "UF_PROPERTY_ID" => $propertyId
                        ]
                    );

                    if (!$resultAdd->isSuccess()) {
                        throw new \Exception();
                    }
                }
            }
        } catch (\Exception $e) {
            $existError = true;
        }

        \CIBlock::clearIblockTagCache(6);

        return !$existError;
    }

    /**
     * @return void
     */
    private static function clearProperty($propertyId)
    {
        if ((int)$propertyId <= 0) {
            return;
        }

        try {
            /** @var DataManager $entityDataClass */
            $entityDataClass = self::getEntityDataClass(self::getHLBlockId());
            $row = $entityDataClass::getList(
                [
                    "filter" => [
                        "UF_PROPERTY_ID" => $propertyId
                    ],
                    "select" => [
                        "ID"
                    ]
                ])->fetch();

            if (!$row) {
                return;
            }

            $entityDataClass::delete($row["ID"]);
        } catch (\Exception $e) {
        }

        \CIBlock::clearIblockTagCache(6);
    }

    public static function getShowCharacteristics()
    {
        try {
            /** @var DataManager $entityDataClass */
            $entityDataClass = self::getEntityDataClass(self::getHLBlockId());
            $currentShowProperties = $entityDataClass::getList()->fetchAll();

            $currentShowProperties = array_map(
                function ($item) {
                    return (int)$item["UF_PROPERTY_ID"];
                },
                $currentShowProperties
            );

        } catch (\Exception $e) {
        }

        return $currentShowProperties;
    }

    //Функция получения экземпляра класса:
    private static function getEntityDataClass($HlBlockId)
        {
        if (empty($HlBlockId) || $HlBlockId < 1) {
            return false;
        }
        $hlblock = HighloadBlockTable::getById($HlBlockId)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();
        return $entity_data_class;
    }

    private static function getHLBlockId()
    {
        $characteristicsHLId = (int)\Bitrix\Main\Config\Option::get(self::MODULE_ID, "characteristics_hl_id",
            0);

        if ($characteristicsHLId < 0) {
            $characteristicsHLId = HighloadBlockTable::getList([
                "filter" => [
                    "NAME" => "HozbergCharacteristics"
                ]
            ])->fetch();

            $characteristicsHLId = (int)$characteristicsHLId["ID"];
        }

        return $characteristicsHLId;
    }
}
