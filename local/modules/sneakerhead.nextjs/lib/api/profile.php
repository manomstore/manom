<?php

namespace Sneakerhead\Nextjs\Api;

use Bitrix\Main\Web\HttpClient;
use \Sneakerhead\Nextjs\Utils;
use \Bitrix\Main\Loader;
use \Bitrix\Main\SystemException;

/**
 * Class Profile
 * @package Sneakerhead\Nextjs\Api
 */
class Profile
{
    /**
     * Profile constructor.
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
     * @param int $userId
     * @return array
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function getProfile($userId = 0, $checkValidAddress = true)
    {
        $profile = array();

        $user = new User;
        $userData = $user->getData($userId);

        if (!empty($userData['id'])) {
            $location = new Location;

            $deliveryAndPaySystemIds = Order::getUserLastOrderDeliveryIdAndPaySystemId();

            $profile = array(
                'id' => $userData['id'],
                'fullName' => implode(
                    ' ',
                    array_filter(array($userData['lastName'], $userData['name'], $userData['secondName']))
                ),
                'email' => $userData['email'],
                'phone' => $userData['phone'],
                'delivery' => $deliveryAndPaySystemIds['deliveryId'],
                'paySystem' => $deliveryAndPaySystemIds['paySystemId'],
                'location' => array(),
                'zip' => 0,
                'address' => '',
                'profileId' => 0,
                'personTypeId' => 1,
                'selfDeliveryPointId' => 0,
                'officeId' => 0,
            );

            $order = array('DATE_UPDATE' => 'DESC');
            $filter = array('USER_ID' => $userData['id']);
            $select = array('ID', 'PERSON_TYPE_ID');
            $result = \CSaleOrderUserProps::GetList($order, $filter, false, false, $select);
            if ($row = $result->Fetch()) {
                $profile['profileId'] = (int)$row['ID'];
                $profile['personTypeId'] = (int)$row['PERSON_TYPE_ID'];

                $filter = array('USER_PROPS_ID' => $row['ID']);
                $select = array('ID', 'CODE', 'VALUE');
                $result = \CSaleOrderUserPropsValue::GetList(array(), $filter, false, false, $select);
                while ($row = $result->Fetch()) {
                    if (empty($row['VALUE'])) {
                        continue;
                    }

                    if ($row['CODE'] === 'FIO') {
                        $profile['fullName'] = $row['VALUE'];
                    }

                    if ($row['CODE'] === 'EMAIL') {
                        $profile['email'] = $row['VALUE'];
                    }

                    if ($row['CODE'] === 'PHONE') {
                        $phone = Utils::formatPhone($row['VALUE']);
                        if (!empty($phone)) {
                            $profile['phone'] = $phone;
                        }
                    }

                    if ($row['CODE'] === 'ZIP') {
                        $profile['zip'] = (int)$row['VALUE'];
                    }

                    if ($row['CODE'] === 'ADDRESS') {
                        $profile['address'] = $row['VALUE'];
                    }

                    if ($row['CODE'] === 'LOCATION') {
                        $locationArray = $location->getById($row['VALUE']);
                        if (isset($locationArray['id'])) {
                            $profile['location'] = array(
                                'id' => $locationArray['id'],
                                'countryId' => $locationArray['countryId'],
                                'isNotRu' => (int)(!empty($locationArray['countryId']) && $locationArray['countryId'] !== 1),
                            );
                        }
                    }

                    if ($row['CODE'] === 'SELF_DELIVERY_POINT_ID') {
                        $profile['selfDeliveryPointId'] = $row['VALUE'];
                    }

                    if ($row['CODE'] === 'DELIVERY_POINT_ID') {
                        $profile['officeId'] = $row['VALUE'];
                    }
                }
            }

            if (empty($profile['location'])) {
                $locationArray = $location->getByName('Москва');
                if (isset($locationArray['id'])) {
                    $profile['location'] = array(
                        'id' => $locationArray['id'],
                        'countryId' => $locationArray['countryId'],
                        'isNotRu' => (int)(!empty($locationArray['countryId']) && $locationArray['countryId'] !== 1),
                    );
                }
            }

	        if ($checkValidAddress && $profile['location']["countryId"] === 1) {
		        $isValidAddress = $this->isValidAddress($profile['address']);
		        if (!$isValidAddress) {
			        $profile['address'] = "";
			        $this->setProfile(["address" => $profile['address']]);
		        }
	        }
        }

        return $profile;
    }

    /**
     * @param int $userId
     * @param array $data
     * @throws SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ObjectPropertyException
     */
    public function setProfile($data, $userId = 0)
    {
        $profile = $this->getProfile($userId, false); //Don't touch the second parameter

        $profileFields = array();

        if (
            !empty($data['fullName']) &&
            (empty($profile['profileId']) || $data['fullName'] !== $profile['fullName'])
        ) {
            $profileFields['FIO'] = $data['fullName'];
        }

        if (
            !empty($data['email']) &&
            (empty($profile['profileId']) || $data['email'] !== $profile['email'])
        ) {
            $profileFields['EMAIL'] = $data['email'];
        }

        $phone = Utils::formatPhone($data['phone']);
        if (
            !empty($phone) &&
            (empty($profile['profileId']) || $phone !== $profile['phone'])
        ) {
            $profileFields['PHONE'] = $phone;
        }

        if (
            !empty($data['locationId']) &&
            (empty($profile['profileId']) || $data['locationId'] !== $profile['location']['id'])
        ) {
            $profileFields['LOCATION'] = $data['locationId'];

        }

        if (
            !empty($data['zip']) &&
            (empty($profile['profileId']) || $data['zip'] !== $profile['zip'])
        ) {
            $profileFields['ZIP'] = $data['zip'];
        }

        if (
            !empty($data['address']) || ($data['address'] === '') &&
            (empty($profile['profileId']) || $data['address'] !== $profile['address'])
        ) {
            $profileFields['ADDRESS'] = $data['address'];
        }

        if (
            !empty($data['selfDeliveryPointId']) &&
            (empty($profile['profileId']) || $data['selfDeliveryPointId'] !== $profile['selfDeliveryPointId'])
        ) {
            $profileFields['SELF_DELIVERY_POINT_ID'] = $data['selfDeliveryPointId'];
        }

        if (
            !empty($data['officeId']) &&
            (empty($profile['profileId']) || $data['officeId'] !== $profile['officeId'])
        ) {
            $profileFields['DELIVERY_POINT_ID'] = $data['officeId'];
        }
        
        if (!empty($profileFields)) {
            $CSaleOrderUserPropsValue = new \CSaleOrderUserPropsValue;

            $orderProperties = $this->getOrderProperties($profile['personTypeId']);
            if (empty($profile['profileId'])) {
                $CSaleOrderUserProps = new \CSaleOrderUserProps;

                $fields = array(
                    'NAME' => $profileFields['FIO'],
                    'USER_ID' => $profile['id'],
                    'PERSON_TYPE_ID' => $profile['personTypeId'],
                );
                if ($id = $CSaleOrderUserProps->Add($fields)) {
                    foreach ($profileFields as $code => $value) {
                        if (empty($orderProperties[$code])) {
                            continue;
                        }

                        $fields = array(
                            'USER_PROPS_ID' => $id,
                            'ORDER_PROPS_ID' => $orderProperties[$code]['id'],
                            'NAME' => $orderProperties[$code]['name'],
                            'VALUE' => $value,
                        );
                        $CSaleOrderUserPropsValue->Add($fields);
                    }
                }
            } else {
                $profileProperties = $this->getProfileProperties($profile['profileId']);
                foreach ($profileFields as $code => $value) {
                    if (empty($orderProperties[$code])) {
                        continue;
                    }

                    $fields = array(
                        'NAME' => $orderProperties[$code]['name'],
                        'VALUE' => $value,
                    );
                    if (isset($profileProperties[$code])) {
                        $CSaleOrderUserPropsValue->Update($profileProperties[$code]['id'], $fields);
                    } else {
                        $fields['USER_PROPS_ID'] = $profile['profileId'];
                        $fields['ORDER_PROPS_ID'] = $orderProperties[$code]['id'];
                        $CSaleOrderUserPropsValue->Add($fields);
                    }
                }
            }
        }
    }

    /**
     * @param int $personTypeId
     * @return array
     */
    public function getOrderProperties($personTypeId = 1)
    {
        $properties = array();

        $filter = array('PERSON_TYPE_ID' => $personTypeId);
        $select = array('ID', 'CODE', 'NAME');
        $result = \CSaleOrderProps::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $properties[$row['CODE']] = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'code' => $row['CODE'],
            );
        }

        return $properties;
    }

    /**
     * @param array $profileData
     * @param array $availablePaySystem
     * @param array $availableDeliveries
     * @return void
     */
    public function updateLastPsAndDelivery(&$profileData,$availablePaySystems,$availableDeliveries)
    {
	    if (!empty($profileData["paySystem"])) {
		    $availablePaySystems = array_map(
			    function ($item) {
				    return $item["id"];
			    },
			    $availablePaySystems
		    );

		    if (!in_array($profileData["paySystem"], $availablePaySystems)) {
			    $profileData["paySystem"] = 0;
		    }
	    }

	    if (!empty($profileData["delivery"])) {
		    $availableDeliveries = array_map(
			    function ($item) {
				    return $item["id"];
			    },
			    $availableDeliveries
		    );

		    if (!in_array($profileData["delivery"], $availableDeliveries)) {
			    $profileData["delivery"] = 0;
		    }
	    }
    }

    /**
     * @param int $profileId
     * @return array
     */
    public function getProfileProperties($profileId)
    {
        $properties = array();

        $filter = array('USER_PROPS_ID' => $profileId);
        $select = array('ID', 'CODE');
        $result = \CSaleOrderUserPropsValue::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $properties[$row['CODE']] = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'code' => $row['CODE'],
            );
        }

        return $properties;
    }

    /**
     * @param string $email
     * @return int
     */
    public function getUserIdByEmail($email)
    {
        $userId = 0;

        $profilesId = array();
        $filter = array('CODE' => 'EMAIL', 'VALUE' => $email);
        $select = array('USER_PROPS_ID');
        $result = \CSaleOrderUserPropsValue::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $profilesId[] = (int)$row['USER_PROPS_ID'];
        }

        if (!empty($profilesId)) {
            $order = array('DATE_UPDATE' => 'DESC');
            $filter = array('ID' => $profilesId);
            $select = array('USER_ID');
            $result = \CSaleOrderUserProps::GetList($order, $filter, false, false, $select);
            if ($row = $result->Fetch()) {
                $userId = (int)$row['USER_ID'];
            }
        }

        return $userId;
    }

	/**
	 * @param int $address
	 * @return bool
	 * @throws SystemException
	 * @throws \Bitrix\Main\ArgumentException
	 * @throws \Bitrix\Main\LoaderException
	 * @throws \Bitrix\Main\ObjectPropertyException
	 */
    private function isValidAddress($address)
    {
	    $address = trim($address);
	    $addressIsValid = false;
	    if (empty($address)) {
		    return true;
	    }

	    $client = new HttpClient();
	    $client->setHeader("Authorization", "Token 13c28158e6b58d73020665b170c93b462e2db582");
	    $client->setHeader("Content-type", "application/json");

	    try {
		    $response = $client->post(
			    "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address",
			    json_encode(
				    [
					    "query" => $address,
				    ]
			    )
		    );

		    $suggestions = json_decode($response, true);
		    $suggestions = $suggestions["suggestions"];
	    } catch (\Exception $e) {
		    $suggestions = [];
	    }

	    if (empty($suggestions)) {
		    return false;
	    }

	    foreach ($suggestions as $suggestion) {
		    if ($suggestion["value"] !== $address) {
			    continue;
		    }

		    $addressIsValid = ((int) $suggestion["data"]["house"] > 0) && ((int) $suggestion["data"]["flat"] > 0);
		    break;
	    }

	    return $addressIsValid;
    }

    /**
     * @return void
     */
    public static function authOutMake()
    {
        global $APPLICATION;
        if ($APPLICATION->GetCurPage() === "/api/user/") {
            return;
        }

        $_SESSION["AUTH_OUT_CHECKOUT"] = "Y";
    }

    /**
     * @return bool
     */
    public static function checkFirstVisit()
    {
        $isAuthOut = $_SESSION["AUTH_OUT_CHECKOUT"] === "Y";

        if ($isAuthOut) {
            self::authOutCancel();
        }

        return $isAuthOut;
    }

    /**
     * @return void
     */
    public static function authOutCancel()
    {
        unset($_SESSION["AUTH_OUT_CHECKOUT"]);
    }
}
