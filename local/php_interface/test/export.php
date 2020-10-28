<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

global $APPLICATION;
global $USER;

if (!$USER->IsAdmin()) {
    die();
}

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
                $(".complete").show();
                $(".complete_status").text("");
            });


            $(".complete").on("click", function (event) {
                event.preventDefault();
                $.ajax({
                    url: '/local/php_interface/1c_exchange.php?type=sale&mode=success',
                    type: 'GET',
                    success: function () {
                        $(".complete_status").text("Обмен завершён");
                    }
                });
            });
        });
    </script>
    <a class="link"
       style="display: none"
       href="/local/php_interface/1c_exchange.php?type=sale&mode=query"
       target="_blank"
    >Открыть выгрузку</a>
    <br>
    <a class="complete"
       style="display: none"
       href="/local/php_interface/1c_exchange.php?type=sale&mode=query"
       target="_blank"
    >Нажмите для завершения обмена</a><br>
    <span class="complete_status"></span>
<?
