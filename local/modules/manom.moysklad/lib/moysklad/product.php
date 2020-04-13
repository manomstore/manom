<?php

namespace Manom\Moysklad\Moysklad;

use \Bitrix\Main\SystemException;
use \MoySklad\Entities\Products\Product as mProduct;
use \MoySklad\Components\Specs\QuerySpecs\QuerySpecs;
use \MoySklad\Lists\EntityList;
use \Exception;

/**
 * Class Product
 * @package Manom\Moysklad\Moysklad
 */
class Product
{
    private $moysklad;

    /**
     * Product constructor.
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
        $result = mProduct::query(
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

        return mProduct::query($this->moysklad, QuerySpecs::create($params))->getList();
    }
}
