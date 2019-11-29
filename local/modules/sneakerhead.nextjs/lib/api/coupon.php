<?php

namespace Sneakerhead\Nextjs\Api;

use \Bitrix\Main\Loader;
use \Bitrix\Main\SystemException;
use \Bitrix\Sale\DiscountCouponsManager;

/**
 * Class Coupon
 * @package Sneakerhead\Nextjs\Api
 */
class Coupon
{
    /**
     * Coupon constructor.
     * @throws SystemException
     * @throws \Bitrix\Main\LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('sale')) {
            throw new SystemException('Не подключен модуль sale');
        }
    }

    /**
     * @return array
     */
    public function getList()
    {
        $result = array(
            'coupon' => '',
            'list' => array(),
        );

        $coupons = DiscountCouponsManager::get(true, array(), true, true);
        if (!empty($coupons)) {
            foreach ($coupons as &$coupon) {
                if ($result['coupon'] === '') {
                    $result['coupon'] = $coupon['COUPON'];
                }

                if (
                    $coupon['STATUS'] === DiscountCouponsManager::STATUS_NOT_FOUND ||
                    $coupon['STATUS'] === DiscountCouponsManager::STATUS_FREEZE
                ) {
                    $coupon['JS_STATUS'] = 'BAD';
                } elseif (
                    $coupon['STATUS'] === DiscountCouponsManager::STATUS_NOT_APPLYED ||
                    $coupon['STATUS'] === DiscountCouponsManager::STATUS_ENTERED
                ) {
                    $coupon['JS_STATUS'] = 'ENTERED';

                    if ($coupon['STATUS'] === DiscountCouponsManager::STATUS_NOT_APPLYED) {
                        $coupon['STATUS_TEXT'] = DiscountCouponsManager::getCheckCodeMessage(DiscountCouponsManager::COUPON_CHECK_OK);
                        $coupon['CHECK_CODE_TEXT'] = array($coupon['STATUS_TEXT']);
                    }
                } else {
                    $coupon['JS_STATUS'] = 'APPLYED';
                }

                $coupon['JS_CHECK_CODE'] = '';

                if (isset($coupon['CHECK_CODE_TEXT'])) {
                    $coupon['JS_CHECK_CODE'] = is_array($coupon['CHECK_CODE_TEXT'])
                        ? implode('<br>', $coupon['CHECK_CODE_TEXT'])
                        : $coupon['CHECK_CODE_TEXT'];
                }

                $result['list'][] = array(
                    'coupon' => $coupon['COUPON'],
                    'status' => $coupon['JS_STATUS'],
                    'statusText' => $coupon['STATUS_TEXT'],
                );
            }

            unset($coupon);
        }

        unset($coupons);

        return $result;
    }

    /**
     * @param string $coupon
     * @return array
     */
    public function add($coupon)
    {
        $return = array(
            'error' => false,
            'message' => '',
        );

        DiscountCouponsManager::init();

        if (!$result = DiscountCouponsManager::add($coupon)) {
            $return['error'] = true;
            $return['message'] = 'Could not apply coupon';
        }

	      $return["result"] = $result;

        return $return;
    }
}
