<?php

namespace Manom\Moysklad;

use \Dotenv\Dotenv;

/**
 * Class Tools
 * @package Manom\Moysklad
 */
class Tools
{
    /**
     * Tools constructor.
     */
    public function __construct()
    {
    }

    /**
     *
     */
    public static function loadEnv(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
    }

    /**
     * @return array
     */
    public static function getAuthData(): array
    {
        $data = array();

        self::loadEnv();

        if (!empty($_ENV['LOGIN']) && !empty($_ENV['PASSWORD'])) {
            $data = array(
                'login' => $_ENV['LOGIN'],
                'password' => $_ENV['PASSWORD'],
            );
        }

        return $data;
    }

    /**
     * @return int
     */
    public static function getProductsIblockId(): int
    {
        $id = 0;

        if (empty($_ENV['PRODUCTS_IBLOCK_ID'])) {
            self::loadEnv();
        }

        if (!empty($_ENV['PRODUCTS_IBLOCK_ID'])) {
            $id = (int)$_ENV['PRODUCTS_IBLOCK_ID'];
        }

        return $id;
    }

    /**
     * @return int
     */
    public static function getOffersIblockId(): int
    {
        $id = 0;

        if (empty($_ENV['OFFERS_IBLOCK_ID'])) {
            self::loadEnv();
        }

        if (!empty($_ENV['OFFERS_IBLOCK_ID'])) {
            $id = (int)$_ENV['OFFERS_IBLOCK_ID'];
        }

        return $id;
    }

    /**
     * @return string
     */
    public static function getMemoryUsageHuman(): string
    {
        return (!function_exists('memory_get_usage')) ? '-' : round(memory_get_usage() / 1024 / 1024, 2).' MB';
    }
}
