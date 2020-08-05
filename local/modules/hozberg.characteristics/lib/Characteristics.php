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
    public static $items = [];

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
            $existCharacteristics = self::get();

            foreach (array_diff($existCharacteristics, $propertyIds) as $propertyId) {
                self::delete($propertyId);
            }

            foreach ($propertyIds as $propertyId) {
                self::add($propertyId);
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
        try {
            self::delete($propertyId);
        } catch (\Exception $e) {
        }

        \CIBlock::clearIblockTagCache(6);
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

        if ($characteristicsHLId <= 0) {
            $characteristicsHLId = HighloadBlockTable::getList([
                "filter" => [
                    "NAME" => "HozbergCharacteristics"
                ]
            ])->fetch();

            $characteristicsHLId = (int)$characteristicsHLId["ID"];
            \Bitrix\Main\Config\Option::set(
                self::MODULE_ID,
                "characteristics_hl_id",
                $characteristicsHLId);
        }

        return $characteristicsHLId;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function get(): array
    {
        try {
            if (empty(self::$items)) {
                /** @var DataManager $entityDataClass */
                $entityDataClass = self::getEntityDataClass(self::getHLBlockId());
                $characteristics = $entityDataClass::getList()->fetchAll();

                foreach ($characteristics as $characteristic) {
                    self::$items[$characteristic["ID"]] = (int)$characteristic["UF_PROPERTY_ID"];
                }
            }

        } catch (\Exception $e) {
        }

        return self::$items;
    }

    /**
     * @param integer $propertyId
     * @return bool
     * @throws \Exception
     */
    public static function add($propertyId): bool
    {

        if ((int)$propertyId <= 0) {
            return false;
        }

        if (self::isPropertyExist($propertyId)) {
            return false;
        }

        /** @var \Bitrix\Main\ORM\Data\DataManager $entityDataClass */
        $entityDataClass = self::getEntityDataClass(self::getHLBlockId());
        $add = $entityDataClass::add([
            "UF_PROPERTY_ID" => $propertyId
        ]);

        if (!$add->isSuccess()) {
            throw new \Exception();
        }
        self::$items[$add->getId()] = $propertyId;
        return true;
    }

    /**
     * @param integer $propertyId
     * @return bool
     * @throws \Exception
     */
    public static function delete($propertyId): bool
    {
        /** @var \Bitrix\Main\ORM\Data\DataManager $entityDataClass */
        $entityDataClass = self::getEntityDataClass(self::getHLBlockId());

        $characteristicId = array_search($propertyId, self::get());

        if (!$characteristicId) {
            return false;
        }

        $delete = $entityDataClass::delete($characteristicId);
        if (!$delete->isSuccess()) {
            throw new \Exception();
        }

        unset(self::$items[$characteristicId]);
        return true;
    }

    /**
     * @param integer $propertyId
     * @return bool
     * @throws \Exception
     */
    public static function isPropertyExist($propertyId): bool
    {
        return in_array($propertyId, self::get());
    }
}
