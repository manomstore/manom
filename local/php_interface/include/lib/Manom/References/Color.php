<?php

namespace Manom\References;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Manom\Exception;

/**
 * Class Color
 * @package Manom
 */
class Color
{
    private $iblockCode = 'colors';
    private $iblockId;

    /**
     * Color constructor.
     * @throws Exception
     * @throws LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('iblock')) {
            throw new Exception('Не подключен модуль iblock');
        }

        $this->iblockId = $this->setIblockId();
        if (empty($this->iblockId)) {
            throw new Exception('Не установлен ид инфоблока');
        }
    }

    /**
     * @return int
     */
    private function setIblockId(): int
    {
        $id = 0;

        $result = \CIBlock::GetList(array(), array('CODE' => $this->iblockCode));
        if ($row = $result->Fetch()) {
            $id = (int)$row['ID'];
        }

        return $id;
    }

    /**
     * @param array $names
     * @return array
     */
    public function getDataByNames($names): array
    {
        $items = array();

        $filter = array('IBLOCK_ID' => $this->iblockId, 'NAME' => $names);
        $select = array('IBLOCK_ID', 'ID', 'CODE', 'NAME', 'PROPERTY_COLOR_CODE');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $items[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'code' => $row['CODE'],
                'name' => $row['NAME'],
                'colorCode' => $row['PROPERTY_COLOR_CODE_VALUE'],
            );
        }

        return $items;
    }
}
