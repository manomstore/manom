<?php

namespace Manom\Nextjs;

/**
 * Class Utils
 * @package Manom\Nextjs
 */
class Utils
{
    /**
     * Utils constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $phone
     * @return string
     */
    public static function formatPhone($phone)
    {
        $result = '';

        $phone = preg_replace('/[^0-9+]/', '', $phone);

        if (
            strlen($phone) > 11 &&
            (
                strpos($phone, '8') === 0 ||
                strpos($phone, '7') === 0
            )
        ) {
            $phone = substr($phone, 0, 11);
        }

        if (strlen($phone) < 7) {
            $result = '';
        }
        if (strlen($phone) === 10) {
            $result = '+7'.$phone;
        } elseif (
            strlen($phone) === 11 &&
            (
                strpos($phone, '8') === 0 ||
                strpos($phone, '7') === 0
            )
        ) {
            $result = substr_replace($phone, '+7', 0, 1);
        } elseif (
            strlen($phone) > 12 &&
            strpos($phone, '+7') === 0
        ) {
            $result = substr($phone, 0, 12);
        } elseif (strlen($phone) > 6 || strpos($phone, '+') === 0) {
            $result = $phone;
        }

        return $result;
    }
}
