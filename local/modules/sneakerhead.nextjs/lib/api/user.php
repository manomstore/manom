<?php

namespace Sneakerhead\Nextjs\Api;

use \Sneakerhead\Nextjs\Utils;
use \Bitrix\Main\UserTable;

/**
 * Class User
 * @package Sneakerhead\Nextjs\Api
 */
class User
{
    /**
     * User constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function IsAuthorized()
    {
        global $USER;

        return $USER->IsAuthorized();
    }

    /**
     * @param array $data
     * @param bool $authorize
     * @param bool $sendMail
     * @return array
     */
    public function add($data, $authorize = false, $sendMail = false)
    {
        if (empty($data['email'])) {
            return array(
                'error' => true,
                'message' => 'email Is Missing',
            );
        }

        $bitrixUser = new \CUser;

        $name = '';
        $lastName = '';
        $secondName = '';
        if (!empty($data['fullName'])) {
            $fullName = explode(' ', $data['fullName']);

            if (count($fullName) > 2) {
                list($lastName, $name, $secondName) = $fullName;
            } elseif (count($fullName) === 2) {
                list($name, $lastName) = $fullName;
            } elseif (count($fullName) === 1) {
                list($name) = $fullName;
            }
        }

        $password = $this->createSalt();

        $result = $bitrixUser->Register(
            $data['email'],
            $name,
            $lastName,
            $password,
            $password,
            $data['email']
        );
        if (empty($result['ID'])) {
            $return = array(
                'error' => true,
                'message' => $bitrixUser->LAST_ERROR,
            );
        } else {
            $userId = (int)$result['ID'];

            $fields = array(
                'NAME' => $name,
                'LAST_NAME' => $lastName,
                'SECOND_NAME' => $secondName,
                'PERSONAL_PHONE' => !empty($data['phone']) ? Utils::formatPhone($data['phone']) : '',
                'PERSONAL_STREET' => !empty($data['address']) ? $data['address'] : '',
            );

            $bitrixUser->Update($userId, $fields);

            if ($authorize) {
                global $USER;
                $USER->Authorize($userId);
            }

            if ($sendMail) {
                $fields = array(
                    'EMAIL' => $data['email'],
                    'NAME' => $name,
                    'LOGIN' => $data['email'],
                    'PASSWORD' => $password,
                );
                \CEvent::SendImmediate('USER_INFO', SITE_ID, $fields, 'N', 2);
            }

            $return = array(
                'error' => false,
                'id' => $userId,
            );
        }

        return $return;
    }

    /**
     * @param string $email
     * @param string $password
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function authorize($email, $password)
    {
        $return = array(
            'error'   => false,
            'result'  => false,
        );

        $userData = $this->getByEmail($email);
        if (empty($userData['id'])) {
            $return = array(
                'error' => true,
                'message' => 'Пользователь с указанным email не найден',
            );
        } else {
            global $USER;

            $result = $USER->Login($email, $password, 'Y');
            if (!empty($result['TYPE']) && $result['TYPE'] === 'ERROR') {
                $return = array(
                    'error' => true,
                    'message' => $result['MESSAGE'],
                );
            }
	        $return["result"] = $result;
        }

        return $return;
    }

    /**
     * @return string
     */
    private function createPassword()
    {
        return substr(md5(mt_rand()), 0, 7);
    }

    /**
     * @return string
     */
    private function createSalt()
    {
        $string = '';

        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        for ($i = 0; $i < 10; $i++) {
            $string .= substr($chars, rand(1, strlen($chars)) - 1, 1);
        }

        return $string;
    }

    /**
     * @param string $email
     * @param bool $active
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getByEmail($email, $active = true)
    {
        $data = array();

        $filter = array('EMAIL' => $email);
        if ($active) {
            $filter['ACTIVE'] = 'Y';
        }

        $result = UserTable::getList(array(
            'filter' => $filter,
            'select' => array(
                'ID',
                'NAME',
                'LAST_NAME',
                'SECOND_NAME',
                'EMAIL',
                'PERSONAL_PHONE',
                'PERSONAL_MOBILE',
            ),
        ));
        if ($row = $result->fetch()) {
            $phone = Utils::formatPhone($row['PERSONAL_PHONE']);
            if (empty($phone)) {
                $phone = Utils::formatPhone($row['PERSONAL_MOBILE']);
            }

            $data = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'lastName' => $row['LAST_NAME'],
                'secondName' => $row['SECOND_NAME'],
                'email' => $row['EMAIL'],
                'phone' => $phone,
            );
        }

        return $data;
    }

    /**
     * @param string $login
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getIdByLogin($login)
    {
        $userId = 0;

        $result = UserTable::getList(array(
            'filter' => array('LOGIN' => $login),
            'select' => array('ID'),
        ));
        if ($row = $result->fetch()) {
            $userId = (int)$row['ID'];
        }

        return $userId;
    }

    /**
     * @param string $email
     * @return int
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getIdByEmail($email)
    {
        $userId = 0;

        $result = UserTable::getList(array(
            'filter' => array('EMAIL' => $email),
            'select' => array('ID'),
        ));
        if ($row = $result->fetch()) {
            $userId = (int)$row['ID'];
        }

        return $userId;
    }

    /**
     * @param int $userId
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getData($userId = 0)
    {
        $data = array();

        if ($this->IsAuthorized()) {
            global $USER;
            $userId = $USER->GetID();
        }

        if ($userId > 0) {
            $result = UserTable::getList(array(
                'filter' => array('ID' => $userId),
                'select' => array(
                    'ID',
                    'NAME',
                    'LAST_NAME',
                    'SECOND_NAME',
                    'EMAIL',
                    'PERSONAL_PHONE',
                    'PERSONAL_MOBILE',
                ),
            ));
            if ($row = $result->fetch()) {
                $phone = Utils::formatPhone($row['PERSONAL_PHONE']);
                if (empty($phone)) {
                    $phone = Utils::formatPhone($row['PERSONAL_MOBILE']);
                }

                $data = array(
                    'id' => (int)$row['ID'],
                    'name' => $row['NAME'],
                    'lastName' => $row['LAST_NAME'],
                    'secondName' => $row['SECOND_NAME'],
                    'email' => $row['EMAIL'],
                    'phone' => $phone,
                );
            }
        }

        return $data;
    }

    /**
     * @param string $email
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function restorePasswordByEmail($email)
    {
        $userData = $this->getByEmail($email);

        if (empty($userData['id'])) {
            $result = array(
                'error' => true,
                'message' => 'Пользователь с указанным email не найден',
            );
        } else {
            $password = $this->createPassword();

            $user = new \CUser;
            if ($user->Update($userData['id'], array('PASSWORD' => $password))) {
                $this->sendEmailPassword($email, $password);

                $result = array(
                    'error' => false,
                    'message' => 'Пароль для пользователя с логином '.$email.
                        ' был успешно изменен. Новый пароль отправлен на электронную почту.',
                );

            } else {
                $result = array(
                    'error' => true,
                    'message' => $user->LAST_ERROR,
                );
            }

        }

        return $result;
    }

    /**
     * @param string $username
     * @param string $password
     * @param int $messageId
     */
    public function sendEmailPassword($username, $password, $messageId = 3)
    {
        $fields = array('USERNAME' => $username, 'PASSWORD' => $password);

        \CEvent::SendImmediate('USER_PASS_REQUEST', SITE_ID, $fields, 'N', $messageId);
    }
}
