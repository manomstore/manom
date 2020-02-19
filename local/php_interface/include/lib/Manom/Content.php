<?php

namespace Manom;

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;

/**
 * Class Content
 * @package Manom
 */
class Content
{
    /**
     * Content constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param int $sectionId
     * @return array
     * @throws Exception
     * @throws LoaderException
     */
    public static function getSectionBanner($sectionId = 0): array
    {
        if (!Loader::includeModule('iblock')) {
            throw new Exception('Не подключен модуль iblock');
        }

        $sectionBanner = array();
        $sectionBannerWithoutCategory = array();

        $order = array('SORT' => 'ASC');
        $filter = array(
            'IBLOCK_ID' => 10,
            'ACTIVE' => 'Y',
        );

        if ((int)$sectionId > 0) {
            $filter[] = array(
                'LOGIC' => 'OR',
                array('PROPERTY_CB_CATEGORY' => false),
                array('PROPERTY_CB_CATEGORY' => $sectionId),
            );
        } else {
            $filter['PROPERTY_CB_CATEGORY'] = false;
        }

        $select = array(
            'IBLOCK_ID',
            'ID',
            'PREVIEW_PICTURE',
            'PROPERTY_CB_BTN_TEXT',
            'PROPERTY_CB_BTN_LINK',
            'PROPERTY_CB_TEXT',
            'PROPERTY_CB_CATEGORY',
        );
        $result = \CIBlockElement::GetList($order, $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            if (empty($sectionBannerWithoutCategory) && empty($row['PROPERTY_CB_CATEGORY_VALUE'])) {
                $img = \CFile::ResizeImageGet(
                    $row['PREVIEW_PICTURE'],
                    array('width' => 1170, 'height' => 390),
                    BX_RESIZE_IMAGE_PROPORTIONAL
                );
                $sectionBannerWithoutCategory = array(
                    'btn_link' => $row['PROPERTY_CB_BTN_LINK_VALUE'],
                    'btn_text' => $row['PROPERTY_CB_BTN_TEXT_VALUE'],
                    'text' => $row['PROPERTY_CB_TEXT_VALUE'],
                    'img' => $img['src'],
                );
            } elseif (empty($sectionBanner) && (int)$sectionId > 0 && (int)$row['PROPERTY_CB_CATEGORY_VALUE'] === (int)$sectionId) {
                $img = \CFile::ResizeImageGet(
                    $row['PREVIEW_PICTURE'],
                    array('width' => 1170, 'height' => 390),
                    BX_RESIZE_IMAGE_PROPORTIONAL
                );
                $sectionBanner = array(
                    'btn_link' => $row['PROPERTY_CB_BTN_LINK_VALUE'],
                    'btn_text' => $row['PROPERTY_CB_BTN_TEXT_VALUE'],
                    'text' => $row['PROPERTY_CB_TEXT_VALUE'],
                    'img' => $img['src'],
                );
            }
        }

        return empty($sectionBanner) ? $sectionBannerWithoutCategory : $sectionBanner;
    }
}
