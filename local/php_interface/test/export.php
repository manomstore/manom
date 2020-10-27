<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
global $APPLICATION;

$request = \Bitrix\Main\Context::getCurrent()->getRequest();
$host = ($request->isHttps() ? "https://" : "http://") . $_SERVER["HTTP_HOST"];
?>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
        $(function () {

            $.ajax({
                url: '/local/php_interface/1c_exchange.php?type=sale&mode=checkauth',
                type: 'GET',
                success: function () {
                    $(".link").show();
                }
            });


            $(".link").on("click", function () {
                $.ajax({
                    url: '/local/php_interface/1c_exchange.php?type=sale&mode=success',
                    type: 'GET'
                });
            });
        });
    </script>
    <a class="link"
       style="display: none"
       href="/local/php_interface/1c_exchange.php?type=sale&mode=query"
       target="_blank"
    >Открыть выгрузку</a>
<?
