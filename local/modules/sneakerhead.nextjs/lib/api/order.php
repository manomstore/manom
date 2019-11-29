<?php

namespace Sneakerhead\Nextjs\Api;

use \Bitrix\Main\Loader;
 use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\Application;
use \Bitrix\Main\Context;
use \Bitrix\Sale\Order as bitrixOrder;
use \Bitrix\Sale\Internals\SiteCurrencyTable;
use \Bitrix\Sale\DiscountCouponsManager;
use \Bitrix\Sale\Shipment;
use \Bitrix\Sale\Delivery\Services\Manager as DeliveryManager;
use \Bitrix\Sale\Delivery\Services\EmptyDeliveryService;
use \Bitrix\Sale\PaySystem\Manager as PaySystemManager;
use \Bitrix\Sale\OrderStatus;
use \Bitrix\Sale\Delivery\CalculationResult;
use \Bitrix\Sale\PriceMaths;
use \Bitrix\Sale\Delivery\ExtraServices\Manager as DeliveryExtraManager;
use \Bitrix\Sale\Internals\OrderTable;
use Sale\Handlers\Delivery\NewpostHandler;

/**
 * Class Order
 * @package Sneakerhead\Nextjs\Api
 */
class Order
{
    private $order;
    private $request;
    private $userId;
    private $userProfile = array();
    private $userFields = array();
    private $fields = array();
    private $deliveryServices = array();
    private $paySystemServices = array();
    private $personTypeId = 1;
    private $defaultFields = array(
        'deliveryId' => 3, //Самовывоз
        'paySystemId' => 1, //Оплата при получении
        'locationId' => 84, //Москва
    );

    /**
     * Order constructor.
     * @param int $userId
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function __construct($userId = 0)
    {
        if (!Loader::includeModule('sale')) {
            throw new SystemException('Не подключен модуль sale');
        }

        $this->setRequest();
        $this->setUserId($userId);
        $this->setUserProfile();
        $this->setUserFields();
        $this->setFields();
    }

    /**
     * @param int $userId
     */
    private function setUserId($userId = 0)
    {
        if (empty($userId)) {
            global $USER;
            $this->userId = $USER->GetID();

            if (!$this->userId) {
                $this->userId = \CSaleUser::GetAnonymousUserID();
            }
        } else {
            $this->userId = $userId;
        }
    }

    /**
     * @throws SystemException
     */
    private function setRequest()
    {
        $this->request = Application::getInstance()->getContext()->getRequest();
    }

    /**
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    private function setUserProfile()
    {
        $profile = new Profile;

        $this->userProfile = $profile->getProfile();
    }

    /**
     *
     */
    private function setUserFields()
    {
        $this->userFields = array(
            'PERSON_TYPE_ID' => $this->personTypeId,
            'PERSON_TYPE_OLD' => false,
            'PAY_SYSTEM_ID' => false,
            'DELIVERY_ID' => false,
            'ORDER_PROP' => array(),
            'DELIVERY_LOCATION' => false,
            'TAX_LOCATION' => false,
            'PAYER_NAME' => false,
            'USER_EMAIL' => false,
            'PROFILE_NAME' => false,
            'PAY_CURRENT_ACCOUNT' => false,
            'CONFIRM_ORDER' => false,
            'FINAL_STEP' => false,
            'ORDER_DESCRIPTION' => false,
            'PROFILE_ID' => false,
            'PROFILE_CHANGE' => false,
            'DELIVERY_LOCATION_ZIP' => false,
            'ZIP_PROPERTY_CHANGED' => 'N',
            'QUANTITY_LIST' => array(),
            'USE_PRELOAD' => false,
        );

        if (!empty($this->userProfile['personTypeId'])) {
            $this->userFields['PERSON_TYPE_ID'] = $this->userProfile['personTypeId'];
            $this->userFields['PERSON_TYPE_OLD'] = $this->userProfile['personTypeId'];
        }

        if (!empty($this->userProfile['paySystem'])) {
            $this->userFields['PAY_SYSTEM_ID'] = $this->userProfile['paySystem'];
        } else {
            $this->userFields['PAY_SYSTEM_ID'] = $this->defaultFields['paySystemId'];
        }

        if (!empty($this->userProfile['delivery'])) {
            $this->userFields['DELIVERY_ID'] = $this->userProfile['delivery'];
        } elseif (!empty($curDeliveryId = Context::getCurrent()->getRequest()->get('deliveryId'))) {
            $this->userFields['DELIVERY_ID'] = $curDeliveryId;
        } else {
            $this->userFields['DELIVERY_ID'] = $this->defaultFields['deliveryId'];
        }

        if (!empty($this->userProfile['location']['id'])) {
            $this->userFields['DELIVERY_LOCATION'] = $this->userProfile['location']['id'];
        } else {
            $this->userFields['DELIVERY_LOCATION'] = $this->defaultFields['locationId'];
        }

        if (!empty($this->userProfile['email'])) {
            $this->userFields['USER_EMAIL'] = $this->userProfile['email'];
        }

        if (!empty($this->userProfile['profileId'])) {
            $this->userFields['PROFILE_ID'] = $this->userProfile['profileId'];
        }

        if (!empty($this->userProfile['zip'])) {
            $this->userFields['DELIVERY_LOCATION_ZIP'] = $this->userProfile['zip'];
        }
    }

    /**
     *
     */
    private function setFields()
    {
        $this->fields = array(
            'DELIVERY' => array(),
            'BASE_LANG_CURRENCY' => SiteCurrencyTable::getSiteCurrency(SITE_ID),
        );
    }

    /**
     * @param int $orderId
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     */
    private function loadBitrixOrder($orderId)
    {
        $this->order = bitrixOrder::load($orderId);
    }

    /**
     * @param int $userId
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \Bitrix\Main\ObjectException
     */
    private function createBitrixOrder($userId)
    {
        $this->order = bitrixOrder::create(Context::getCurrent()->getSite(), $userId);
    }

    /**
     * @param array $request
     */
    private function initProperties($request)
    {
        $properties = array(
            'FIO' => !empty($request['fullName']) ? $request['fullName'] : '',
            'EMAIL' => !empty($request['email']) ? $request['email'] : '',
            'PHONE' => !empty($request['phone']) ? $request['phone'] : '',
            'ZIP' => !empty($request['zip']) ? $request['zip'] : '',
            'LOCATION' => !empty($request['locationId']) ? $request['locationId'] : 0,
            'ADDRESS' => !empty($request['address']) ? $request['address'] : '',
        );

        $propertyCollection = $this->order->getPropertyCollection();
        foreach ($propertyCollection as $property) {
            if ($property->isUtil()) {
                continue;
            }

            $propertyData = $property->getProperty();

            if ($propertyData['CODE'] === 'ADDRESS') {
                $this->userFields['ORDER_PROP'][$propertyData['ID']] = $properties[$propertyData['CODE']];
            }

            if (empty($properties[$propertyData['CODE']])) {
                continue;
            }

            if ($propertyData['IS_LOCATION'] === 'Y') {
                if ($deliveryLocationProperty = $propertyCollection->getDeliveryLocation()) {
                    $deliveryLocationProperty->setValue(
                        \CSaleLocation::getLocationCODEbyID($properties[$propertyData['CODE']])
                    );
                }
            } else {
                $property->setValue($properties[$propertyData['CODE']]);
            }
        }

        $events = GetModuleEvents('sale', 'OnSaleComponentOrderProperties', true);
        foreach ($events as $event) {
            ExecuteModuleEventEx($event, array(&$this->userFields, $this->request, array(), &$this->fields));
        }
    }

    /**
     * @return \Bitrix\Sale\Shipment
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\NotSupportedException
     */
    private function initShipment()
    {
        $shipmentCollection = $this->order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        $shipment->setField('CURRENCY', $this->order->getCurrency());

        foreach ($this->order->getBasket() as $item) {
            $shipmentItem = $shipmentItemCollection->createItem($item);
            $shipmentItem->setQuantity($item->getQuantity());
        }

        return $shipment;
    }

    /**
     * @param Shipment $shipment
     * @param int $deliveryId
     * @param int $selfDeliveryPointId
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\NotSupportedException
     */
    private function initDelivery(Shipment $shipment, $deliveryId, $selfDeliveryPointId = 0)
    {
        $this->deliveryServices = DeliveryManager::getRestrictedObjectsList($shipment);

        $shipmentCollection = $shipment->getCollection();
        $order = $shipmentCollection->getOrder();

        if (empty($this->deliveryServices)) {
            $service = DeliveryManager::getById(EmptyDeliveryService::getEmptyDeliveryServiceId());

            $shipment->setFields(array(
                'DELIVERY_ID' => $service['ID'],
                'DELIVERY_NAME' => $service['NAME'],
                'CURRENCY' => $order->getCurrency(),
            ));
        } else {
            if (isset($this->deliveryServices[$deliveryId])) {
                $delivery = $this->deliveryServices[$deliveryId];
            } else {
                $delivery = reset($this->deliveryServices);
                $deliveryId = $delivery->getId();
            }

            $order->isStartField();

            $shipment->setFields(array(
                'DELIVERY_ID' => $deliveryId,
                'DELIVERY_NAME' => $delivery->isProfile() ? $delivery->getNameWithParent() : $delivery->getName(),
                'CURRENCY' => $order->getCurrency(),
            ));

            $deliveryStoreList = DeliveryExtraManager::getStoresList($deliveryId);
            if (!empty($deliveryStoreList)) {
                if (
                    empty($selfDeliveryPointId) ||
                    !in_array($selfDeliveryPointId, $deliveryStoreList)
                ) {
                    $selfDeliveryPointId = current($deliveryStoreList);
                }

                $shipment->setStoreId($selfDeliveryPointId);
            }

            $shipmentCollection->calculateDelivery();

            $order->doFinalAction(true);
        }
    }

    /**
     * @param int $paySystemId
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\NotSupportedException
     */
    private function initPayment($paySystemId)
    {
        $paymentCollection = $this->order->getPaymentCollection();
        $payment = $paymentCollection->createItem();
        $paySystemService = PaySystemManager::getObjectById($paySystemId);
        $payment->setFields(array(
            'PAY_SYSTEM_ID' => $paySystemService->getField('PAY_SYSTEM_ID'),
            'PAY_SYSTEM_NAME' => $paySystemService->getField('NAME'),
            'SUM' => $this->order->getPrice(),
        ));
    }

    /**
     * @param bitrixOrder $order
     * @return mixed|null
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     */
    private function getCurrentShipment(bitrixOrder $order)
    {
        foreach ($order->getShipmentCollection() as $shipment) {
            if (!$shipment->isSystem()) {
                return $shipment;
            }
        }

        return null;
    }

    /**
     * @return bitrixOrder
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     */
    private function getOrderClone()
    {
        $orderClone = $this->order->createClone();

        $clonedShipment = $this->getCurrentShipment($orderClone);
        if ($clonedShipment !== null) {
            $clonedShipment->setField('CUSTOM_PRICE_DELIVERY', 'N');
        }

        return $orderClone;
    }

    /**
     * @param array $request
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    private function makeOrder($request = array())
    {
        DiscountCouponsManager::init();

        $events = GetModuleEvents('sale', 'OnSaleComponentOrderOneStepDiscountBefore', true);
        foreach ($events as $event) {
            ExecuteModuleEventEx($event, array(&$this->fields, &$this->userFields, array(), true));
        }

        $this->createBitrixOrder($this->userId);

        $this->order->isStartField();

        $basketObject = new Basket;
        $this->order->setBasket($basketObject->getBitrixBasket()->getOrderableItems());

        $this->order->setPersonTypeId($this->personTypeId);

        $this->initProperties($request);

        $taxes = $this->order->getTax();
        $taxes->setDeliveryCalculate(false);

        $shipment = $this->initShipment($this->order);

        $this->order->doFinalAction(true);

        if (!empty($request['deliveryId'])) {
            $deliveryId = $request['deliveryId'];
        } elseif (!empty($this->userFields['DELIVERY_ID'])) {
            $deliveryId = $this->userFields['DELIVERY_ID'];
        }

        $selfDeliveryPointId = 0;
        if (!empty($request['selfDeliveryPointId'])) {
            $selfDeliveryPointId = $request['selfDeliveryPointId'];
        }

        if (!empty($deliveryId)) {
            $this->initDelivery($shipment, $deliveryId, $selfDeliveryPointId);
        }

        if (!empty($request['paySystemId'])) {
            $paySystemId = $request['paySystemId'];
        } elseif (!empty($this->userFields['PAY_SYSTEM_ID'])) {
            $paySystemId = $this->userFields['PAY_SYSTEM_ID'];
        }

        if (!empty($paySystemId)) {
            $this->initPayment($paySystemId);
        }

        $this->order->getShipmentCollection()->calculateDelivery();
    }

    /**
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    private function calculateDeliveries()
    {
        $this->fields['DELIVERY'] = array();
        $problemDeliveries = array();

        if (!empty($this->deliveryServices)) {
            $orderClone = null;
            $anotherDeliveryCalculated = false;

            $shipment = $this->getCurrentShipment($this->order);

            foreach ($this->deliveryServices as $deliveryId => $deliveryObject) {
                $delivery = array();
                $calcResult = false;
                $calcOrder = false;

                if ($shipment !== null && (int)$shipment->getDeliveryId() === (int)$deliveryId) {
                    $delivery['CHECKED'] = 'Y';
                    $calcResult = $deliveryObject->calculate($shipment);
                    $calcOrder = $this->order;
                } else {
                    $anotherDeliveryCalculated = true;

                    if (empty($orderClone)) {
                        $orderClone = $this->getOrderClone($this->order);
                    }

                    $orderClone->isStartField();

                    $clonedShipment = $this->getCurrentShipment($orderClone);
                    if ($clonedShipment !== null) {
                        $clonedShipment->setField('DELIVERY_ID', $deliveryId);

                        $calculationResult = $orderClone->getShipmentCollection()->calculateDelivery();
                        if ($calculationResult->isSuccess()) {
                            $calcDeliveries = $calculationResult->get('CALCULATED_DELIVERIES');
                            $calcResult = reset($calcDeliveries);
                        }

                        if (empty($calcResult)) {
                            $calcResult = new CalculationResult;
                        }

                        $orderClone->doFinalAction(true);

                        $calcOrder = $orderClone;
                    }
                }

                if (!empty($calcResult) && !empty($calcOrder) && $calcResult->isSuccess()) {
                    $delivery['PRICE'] = PriceMaths::roundPrecision($calcResult->getPrice());
                    $delivery['PRICE_FORMATED'] = SaleFormatCurrency(
                        $delivery['PRICE'],
                        $calcOrder->getCurrency()
                    );

                    $currentCalcDeliveryPrice = PriceMaths::roundPrecision($calcOrder->getDeliveryPrice());
                    if ($currentCalcDeliveryPrice >= 0 && (int)$delivery['PRICE'] !== (int)$currentCalcDeliveryPrice) {
                        $delivery['DELIVERY_DISCOUNT_PRICE'] = $currentCalcDeliveryPrice;
                        $delivery['DELIVERY_DISCOUNT_PRICE_FORMATED'] = SaleFormatCurrency(
                            $delivery['DELIVERY_DISCOUNT_PRICE'],
                            $calcOrder->getCurrency()
                        );
                    }

                    if ($deliveryObject instanceof NewpostHandler) {
                        $delivery['PERIOD_TEXT'] = "2-21 день";
                    } elseif ($calcResult->getPeriodDescription() !== '') {
                        $delivery['PERIOD_TEXT'] = $calcResult->getPeriodDescription();
                    }

                    $delivery['CALCULATE_DESCRIPTION'] = $calcResult->getDescription();
                } else {
                    if (count($calcResult->getErrorMessages()) > 0) {
                        foreach ($calcResult->getErrorMessages() as $message) {
                            $delivery['CALCULATE_ERRORS'] .= $message.'<br>';
                        }
                    } else {
                        $delivery['CALCULATE_ERRORS'] = Loc::getMessage('NEXTJS_API_ORDER_DELIVERY_CALC_FAILED');
                    }

                    if ($delivery['CHECKED'] !== 'Y') {
                        $problemDeliveries[$deliveryId] = $delivery;
                        continue;
                    }
                }

                $this->fields['DELIVERY'][$deliveryId] = $delivery;
            }

            if ($anotherDeliveryCalculated) {
                $this->order->doFinalAction(true);
            }
        }

        if (!empty($problemDeliveries)) {
            $this->fields['DELIVERY'] += $problemDeliveries;
        }

        $events = GetModuleEvents('sale', 'OnSaleComponentOrderDeliveriesCalculated', true);
        $eventParameters = array(
            $this->order,
            &$this->userFields,
            $this->request,
            array(),
            &$this->fields,
            &$this->deliveryServices,
            &$this->paySystemServices,
        );

        require_once $_SERVER['DOCUMENT_ROOT'].'/local/components/sneakerhead/sale.order.ajax/class.php';

	    foreach ($events as $event) {
            ExecuteModuleEventEx($event, $eventParameters);
	    }
    }

    /**
     * @throws SystemException
     * @throws \Bitrix\Main\LoaderException
     */
    private function obtainDelivery()
    {
        $storesId = array();

        if (!empty($this->deliveryServices)) {
            foreach ($this->deliveryServices as $deliveryObject) {
                $delivery = $this->fields['DELIVERY'][$deliveryObject->getId()];

                $delivery['ID'] = (int)$deliveryObject->getId();
                $delivery['NAME'] = $deliveryObject->isProfile() ? $deliveryObject->getNameWithParent() : $deliveryObject->getName();
                $delivery['OWN_NAME'] = $deliveryObject->getName();
                $delivery['DESCRIPTION'] = $deliveryObject->getDescription();
                $delivery['FIELD_NAME'] = 'DELIVERY_ID';
                $delivery['CURRENCY'] = $this->order->getCurrency();
                $delivery['SORT'] = $deliveryObject->getSort();
                $delivery['EXTRA_SERVICES'] = $deliveryObject->getExtraServices()->getItems();
                $delivery['STORE'] = DeliveryExtraManager::getStoresList($deliveryObject->getId());

                if (!empty($delivery['STORE']) && is_array($delivery['STORE'])) {
                    foreach ($delivery['STORE'] as $id) {
                        // fix for https://trello.com/c/Uwsel1yj
                        if ((int)$id === 5) {
                            continue;
                        }
                        //
                        $storesId[$id] = $id;
                    }

                    $store = new Store;
                    $delivery['STORES'] = $store->getStoresById($storesId);
                }

                $this->fields['DELIVERY'][(int)$delivery['ID']] = $delivery;
            }
        }

        $events = GetModuleEvents('sale', 'OnSaleComponentOrderOneStepDelivery', true);
        foreach ($events as $event) {
            ExecuteModuleEventEx($event, array(&$this->fields, &$this->userFields, array(), true));
        }
    }

    /**
     * @param array $request
     * @return array
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\NotSupportedException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    public function getDeliveries($request)
    {
        $this->makeOrder($request);
        $this->calculateDeliveries();
        $this->obtainDelivery();

        return $this->fields['DELIVERY'];
    }

    /**
     * @param array $request
     * @return array
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectNotFoundException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function save($request)
    {
        $return = array(
            'error' => false,
            'message' => '',
        );

        $basketObject = new Basket;

        $basketObject->setLocationId($request["locationId"]);

        $items = $basketObject->getItems();
        if (empty($items)) {
            return array(
                'error' => true,
                'message' => Loc::getMessage('NEXTJS_API_ORDER_EMPTY_BASKET'),
            );
        }

        foreach ($items as $item) {
            if ($item['available'] !== 1) {
                return array(
                    'error' => true,
                    'message' => Loc::getMessage('NEXTJS_ORDER_PRODUCT_NA'),
                );
            }
            if ($item['availableInCurrentLocation'] !== 1) {
                return array(
                    'error' => true,
                    'message' => Loc::getMessage('NEXTJS_ORDER_PRODUCT_REGION_NA'),
                );
            }
        }

        $this->makeOrder($request);

        $this->order->setField('STATUS_ID', OrderStatus::getInitialStatus());
        $this->order->setField('USER_DESCRIPTION', !empty($request['comment']) ? $request['comment'] : '');

        $this->setPickPointAddress($request['officeId'], $request['deliveryId']);

        $result = $this->order->save();

        DiscountCouponsManager::clear(true);

        if ($result->isSuccess()) {
            $return['id'] = (int)$this->order->getId();

            $events = GetModuleEvents('sale', 'OnSaleComponentOrderOneStepComplete', true);
            foreach ($events as $event) {
                ExecuteModuleEventEx($event, array($return['id'], $this->order->getFieldValues(), array()));
            }

            $_SESSION['SALE_ORDER_ID'][] = $return['id'];
        } else {
            $return['error'] = true;
            $return['message'] = $result->getErrorMessages();
        }

        return $return;
    }

    /**
     * @param Basket $basket
     *
     * @return array
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public static function getOrderFromBasket(Basket $basket)
    {
        $items = $basket->getItems();

        if (empty($items)) {
            return [];
        }

//        $discounts = $basket->getDiscounts();

        $orderPrice = $basket->getSum();
        $orderOldPrice = 0;
        $orderDiscountValue = 0;
        $orderQuantity = 0;
        $discounts = [];

        foreach ($items as $item) {
            $orderQuantity += $item['quantity'];

            if (!empty($item['discount'])) {
                $orderOldPrice += $item['oldPrice'] * $item['quantity'];
                $orderDiscountValue += $item['discount']['value'] * $item['quantity'];

                if (isset($discounts[$item['discount']['name']])) {
                    $discounts[$item['discount']['name']]['value'] += $item['discount']['value'];
                    $discounts[$item['discount']['name']]['valueFormat'] = number_format(
                        $item['discount']['value'],
                        0,
                        '',
                        ' '
                    );
                } else {
                    $discounts[$item['discount']['name']] = $item['discount'];
                }
            }
        }

        $order = [
            'price' => $orderPrice,
            'priceFormat' => number_format($orderPrice, 0, '', ' '),
            'positions' => count($items),
            'quantity' => $orderQuantity,
        ];

        if (!empty($discounts)) {
            $order['oldPrice'] = $orderOldPrice;
            $order['oldPriceFormat'] = number_format($orderOldPrice, 0, '', ' ');
            $order['discountValue'] = $orderDiscountValue;
            $order['discountValueFormat'] = number_format($orderDiscountValue, 0, '', ' ');
        }

        return $order;
    }

    /**
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public static function getUserLastOrderDeliveryIdAndPaySystemId()
    {
        $deliveryId = 0;
        $paySystemId = 0;

        global $USER;
        if ($userId = $USER->GetID()) {
            $result = OrderTable::getList(array(
                'order' => array('DATE_INSERT' => 'DESC'),
                'filter' => array('USER_ID' => $userId),
                'select' => array('DELIVERY_ID', 'PAY_SYSTEM_ID'),
            ));
            if ($row = $result->fetch()) {
                $deliveryId = (int)$row['DELIVERY_ID'];
                $paySystemId = (int)$row['PAY_SYSTEM_ID'];
            }
        }

        return array(
            'deliveryId' => $deliveryId,
            'paySystemId' => $paySystemId,
        );
    }

    /**
     * @param int $officeId
     * @param int $deliveryId
     * @return bool
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectNotFoundException
     */
    private function setPickPointAddress($officeId, $deliveryId)
    {
        if (empty($officeId) || empty($deliveryId)) {
            return false;
        }

        $this->calculateDeliveries();
        $this->obtainDelivery();

        $officeData = $this->fields['DELIVERY'][$deliveryId]['office_data'][$officeId];

        if (empty($officeData)) {
            return false;
        }

        $profileId = $this->fields['edost']['format']['active']['profile'];
        if (empty($profileId)) {
            $profileId = 57;
        }

        $address = 'адрес: '.$officeData['address_full'].', ';
        $address .= 'телефон: '.$officeData['tel'].', ';
        $address .= 'часы работы: '.$officeData['schedule'].', ';
        $address .= 'код филиала: '.$officeData['code'].'/'.$officeData['id'].'/'.$officeData['type'].'/'.$profileId;

        $propertyCollection = $this->order->getPropertyCollection();
        foreach ($propertyCollection as $property) {
            if ($property->isUtil()) {
                continue;
            }

            $propertyData = $property->getProperty();
            if ($propertyData['CODE'] === 'ADDRESS') {
                $property->setValue($address);
            }
        }

        return true;
    }
}