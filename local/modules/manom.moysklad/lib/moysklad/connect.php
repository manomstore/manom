<?php

namespace Manom\Moysklad\Moysklad;

use \Bitrix\Main\SystemException;
use \MoySklad\MoySklad;
use \Manom\Moysklad\Tools;

/**
 * Class Connect
 * @package Citycycle\Moysklad\Moysklad
 */
class Connect
{
    private static $instance;

    /**
     * Connect constructor.
     */
    private function __construct()
    {
    }

    /**
     *
     */
    private function __clone()
    {
    }

    /**
     *
     */
    private function __wakeup()
    {
    }

    /**
     * @return MoySklad
     * @throws SystemException
     */
    public static function getInstance(): MoySklad
    {
        $authData = Tools::getAuthData();
        if (empty($authData)) {
            throw new SystemException('Отсутствуют логин и пароль для авторизации в Мой Склад');
        }

        if (!(isset(static::$instance) && static::$instance)) {
            static::$instance = MoySklad::getInstance($authData['login'], $authData['password']);
        }

        return static::$instance;
    }

}
