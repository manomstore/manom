<?php
/**
 * Created by PhpStorm.
 * User: Вячеслав
 * Date: 17.06.2019
 * Time: 10:52
 */

spl_autoload_register(
/**
 * @param string $class
 */
    function ($class) {
        $ns = 'Roistat';
        $prefixes = array(
            "{$ns}\\" => array(
                __DIR__ . '/classes',
                __DIR__ . '/configs',
                __DIR__ . '/libs',
            ),
        );
        foreach ($prefixes as $prefix => $dirs) {
            $prefix_len = strlen($prefix);
            if (substr($class, 0, $prefix_len) !== $prefix) {
                continue;
            }
            $class = substr($class, $prefix_len);
            $part = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
            foreach ($dirs as $dir) {
                $dir = str_replace('/', DIRECTORY_SEPARATOR, $dir);
                $file = $dir . DIRECTORY_SEPARATOR . $part;
                if (is_readable($file)) {
                    require $file;
                    return;
                }
            }
        }
    });