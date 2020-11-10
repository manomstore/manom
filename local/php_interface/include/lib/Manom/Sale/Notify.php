<?php

namespace Manom\Sale;

use Bitrix\Main;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Internals;
use Bitrix\Sale\Helpers;
use Bitrix\Sale\{Order,};
use Bitrix\Sale\OrderBase;
use Bitrix\Sale\Result;

/**
 * Class Notify
 * @package Manom\Sale
 */
class Notify extends \Bitrix\Sale\Notify
{

    /**
     * @param Internals\Entity $entity
     *
     * @return Result
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentTypeException
     * @throws Main\ArgumentException
     * @throws Main\ObjectNotFoundException
     * @throws Main\NotImplementedException
     */
    public static function sendOrderConfirmAsNew(Internals\Entity $entity)
    {
        $result = new Result();

        if (static::isNotifyDisabled()) {
            return $result;
        }

        if (!$entity instanceof Order) {
            throw new Main\ArgumentTypeException('entity', '\Bitrix\Sale\Order');
        }

        if (self::hasSentEvent($entity->getId(), static::EVENT_ORDER_NEW_SEND_EMAIL_EVENT_NAME)) {
            return $result;
        }

        $by = $sort = '';

        $separator = "<br/>";

        $eventName = static::EVENT_ORDER_NEW_SEND_EMAIL_EVENT_NAME;

        $filter = array(
            "EVENT_NAME" => $eventName,
            'ACTIVE' => 'Y',
        );

        if ($entity instanceof OrderBase) {
            $filter['SITE_ID'] = $entity->getSiteId();
        } elseif (defined('SITE_ID') && SITE_ID != '') {
            $filter['SITE_ID'] = SITE_ID;
        }

        $res = \CEventMessage::GetList($by, $sort, $filter);
        if ($eventMessage = $res->Fetch()) {
            if ($eventMessage['BODY_TYPE'] == 'text') {
                $separator = "\n";
            }
        }

        $basketList = '';
        /** @var Basket $basket */
        $basket = $entity->getBasket();
        if ($basket) {
            $basketTextList = $basket->getListOfFormatText();
            if (!empty($basketTextList)) {
                foreach ($basketTextList as $basketItemCode => $basketItemData) {
                    $basketList .= $basketItemData . $separator;
                }
            }
        }

        $fields = Array(
            "ORDER_ID" => $entity->getField("ACCOUNT_NUMBER"),
            "ORDER_REAL_ID" => $entity->getField("ID"),
            "ORDER_ACCOUNT_NUMBER_ENCODE" => urlencode(urlencode($entity->getField("ACCOUNT_NUMBER"))),
            "ORDER_DATE" => $entity->getDateInsert()->toString(),
            "ORDER_USER" => static::getUserName($entity),
            "PRICE" => SaleFormatCurrency($entity->getPrice(), $entity->getCurrency()),
            "BCC" => Main\Config\Option::get("sale", "order_email", "order@" . $_SERVER["SERVER_NAME"]),
            "EMAIL" => static::getUserEmail($entity),
            "ORDER_LIST" => $basketList,
            "SALE_EMAIL" => Main\Config\Option::get("sale", "order_email", "order@" . $_SERVER["SERVER_NAME"]),
            "DELIVERY_PRICE" => $entity->getDeliveryPrice(),
            "ORDER_PUBLIC_URL" => Helpers\Order::isAllowGuestView($entity) ? Helpers\Order::getPublicLink($entity) : ""
        );

        $send = true;

        foreach (GetModuleEvents("sale", static::EVENT_ON_ORDER_NEW_SEND_EMAIL, true) as $oldEvent) {
            if (ExecuteModuleEventEx($oldEvent, array($entity->getId(), &$eventName, &$fields)) === false) {
                $send = false;
            }
        }

        if ($send) {
            $event = new \CEvent;
            $event->Send($eventName, $entity->getField('LID'), $fields, "Y", "", array(), static::getOrderLanguageId($entity));
        }

        static::addSentEvent($entity->getId(), static::EVENT_ORDER_NEW_SEND_EMAIL_EVENT_NAME);

        \CSaleMobileOrderPush::send(static::EVENT_MOBILE_PUSH_ORDER_CREATED, array("ORDER" => static::getOrderFields($entity)));

        return $result;
    }

    /**
     * @param $code
     * @param $event
     *
     * @return bool
     */
    protected static function hasSentEvent($code, $event)
    {
        if (!array_key_exists($code, static::$sentEventList)) {
            return false;
        }

        if (in_array($event, static::$sentEventList[$code])) {
            return true;
        }

        return false;
    }


    /**
     * @param $code
     * @param $event
     *
     * @return bool
     */
    protected static function addSentEvent($code, $event)
    {
        if (!static::hasSentEvent($code, $event)) {
            static::$sentEventList[$code][] = $event;
            return true;
        }

        return false;
    }
}