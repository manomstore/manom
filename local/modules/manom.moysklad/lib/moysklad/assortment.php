<?php

namespace Manom\Moysklad\Moysklad;

use \Bitrix\Main\SystemException;
use \MoySklad\Entities\Assortment as mAssortment;
use \MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use \MoySklad\Lists\EntityList;
use \Exception;

/**
 * Class Assortment
 * @package Manom\Moysklad\Moysklad
 */
class Assortment
{
    private $moysklad;

    /**
     * Assortment constructor.
     * @throws SystemException
     */
    public function __construct()
    {
        $this->moysklad = Connect::getInstance();
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getElementsCount(): int
    {
        $result = mAssortment::query(
            $this->moysklad,
            QuerySpecs::create(
                [
                    'maxResults' => 1,
                ]
            )
        )->getList();

        return (int)$result->getMeta()->size;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return EntityList
     * @throws Exception
     */
    public function getElements($limit = 0, $offset = 0): EntityList
    {
        $params = array(
            'offset' => $offset,
        );

        if ($limit > 0) {
            $params['maxResults'] = $limit;
        }

        return mAssortment::query($this->moysklad, QuerySpecs::create($params))->getList();
    }

    /**
     * @param string $href
     * @return string
     */
    public function getUuidByHref($href): string
    {
        $array = explode('=', $href);
        if (empty($array)) {
            return '';
        }

        return array_pop($array);
    }
}
