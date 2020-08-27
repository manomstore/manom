<?php

namespace Manom\Airtable\Bitrix;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;

/**
 * Class Section
 * @package Manom\Airtable\Bitrix
 */
class Section
{
    private $iblockId;

    /**
     * Section constructor.
     * @param int $iblockId
     * @throws LoaderException
     * @throws SystemException
     */
    public function __construct($iblockId)
    {
        if (empty($iblockId)) {
            throw new SystemException('Не указан ид инфоблока');
        }

        if (!Loader::includeModule('iblock')) {
            throw new SystemException('Не подключен модуль iblock');
        }

        $this->iblockId = $iblockId;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        $items = array();

        $filter = array('IBLOCK_ID' => $this->iblockId, "GLOBAL_ACTIVE" => "Y");
        $select = array('IBLOCK_ID', 'ID', 'NAME', 'IBLOCK_SECTION_ID');
        $result = \CIBlockSection::GetList(array(), $filter, false, $select);
        while ($row = $result->fetch()) {
            $items[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'parent' => $row['IBLOCK_SECTION_ID'],
            );
        }

        foreach ($items as &$item) {
            if ($item["parent"] && array_key_exists($item["parent"], $items)) {
                $item["parent"] = $items[$item["parent"]];
            } else {
                $item["parent"] = [];
            }
        }
        unset($item);

        return $items;
    }
}
