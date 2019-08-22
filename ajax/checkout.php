<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

if (!defined('PUBLIC_AJAX_MODE')) {
    define('PUBLIC_AJAX_MODE', true);
}

header('Content-type: application/json');

$result = [
    'type' => 'ok',
];

$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$type = $request->get("type");

try {
    if (!check_bitrix_sessid()) {
        throw new \Exception();
    }

    switch ($type) {
        case "checkEmail":
            $email = (string)$request->get("email");
            if (empty($email)) {
                throw new \Exception();
            }

            $userExist = (int)\CUser::GetList($by, $order, ["EMAIL" => $email])->GetNext();
            $result["exist"] = $userExist;
            break;
        case "authorize":
            if (!$request->isPost()) {
                throw new \Exception();
            }

            $email = (string)$request->get("email");
            $password = (string)$request->get("password");

            if (empty($email)) {
                throw new \Exception("Не указан e-mail");
            }

            if (empty($password)) {
                throw new \Exception("Не указан пароль");
            }

            $userExist = (int)\CUser::GetList($by, $order, ["EMAIL" => $email])->GetNext();

            if (!$userExist) {
                throw new \Exception("Пользователь с указанным e-mail не найден");
            }
            global $USER;

            $authResult = $USER->Login($email, $password, 'Y');
            if (!empty($authResult['TYPE']) && $authResult['TYPE'] === 'ERROR') {
                throw new \Exception($authResult['MESSAGE']);
            }

            $result["success"] = (int)($authResult === true);

            break;
        case "updateBasket":
            ob_start();
            $APPLICATION->IncludeComponent(
                "bitrix:sale.basket.basket",
                "manom",
                [
                    "ACTION_VARIABLE" => "action",
                    // Название переменной действия
                    "AUTO_CALCULATION" => "Y",
                    // Автопересчет корзины
                    "TEMPLATE_THEME" => "blue",
                    // Цветовая тема
                    "COLUMNS_LIST" => [
                        0 => "NAME",
                        1 => "DISCOUNT",
                        2 => "WEIGHT",
                        3 => "DELETE",
                        4 => "DELAY",
                        5 => "TYPE",
                        6 => "PRICE",
                        7 => "QUANTITY",
                    ],
                    "COMPONENT_TEMPLATE" => ".default",
                    "COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
                    "GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
                    // Текст заголовка "Подарки"
                    "GIFTS_CONVERT_CURRENCY" => "Y",
                    // Показывать цены в одной валюте
                    "GIFTS_HIDE_BLOCK_TITLE" => "N",
                    // Скрыть заголовок "Подарки"
                    "GIFTS_HIDE_NOT_AVAILABLE" => "N",
                    // Не отображать товары, которых нет на складах
                    "GIFTS_MESS_BTN_BUY" => "Выбрать",
                    // Текст кнопки "Выбрать"
                    "GIFTS_MESS_BTN_DETAIL" => "Подробнее",
                    // Текст кнопки "Подробнее"
                    "GIFTS_PAGE_ELEMENT_COUNT" => "4",
                    // Количество элементов в строке
                    "GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
                    // Название переменной, в которой передаются характеристики товара
                    "GIFTS_PRODUCT_QUANTITY_VARIABLE" => "",
                    // Название переменной, в которой передается количество товара
                    "GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
                    // Показывать процент скидки
                    "GIFTS_SHOW_IMAGE" => "Y",
                    // Показывать изображение
                    "GIFTS_SHOW_NAME" => "Y",
                    // Показывать название
                    "GIFTS_SHOW_OLD_PRICE" => "Y",
                    // Показывать старую цену
                    "GIFTS_TEXT_LABEL_GIFT" => "Подарок",
                    // Текст метки "Подарка"
                    "GIFTS_PLACE" => "BOTTOM",
                    // Вывод блока "Подарки"
                    "HIDE_COUPON" => "N",
                    // Спрятать поле ввода купона
                    "OFFERS_PROPS" => [  // Свойства, влияющие на пересчет корзины
                        0 => "SIZES_SHOES",
                        1 => "SIZES_CLOTHES",
                    ],
                    "PATH_TO_ORDER" => "/personal/order.php",
                    // Страница оформления заказа
                    "PRICE_VAT_SHOW_VALUE" => "N",
                    // Отображать значение НДС
                    "QUANTITY_FLOAT" => "N",
                    // Использовать дробное значение количества
                    "SET_TITLE" => "Y",
                    // Устанавливать заголовок страницы
                    "USE_GIFTS" => "Y",
                    // Показывать блок "Подарки"
                    "USE_PREPAYMENT" => "N",
                    // Использовать предавторизацию для оформления заказа (PayPal Express Checkout)
                ],
                false
            );
            $result["html"] = ob_get_clean();
            break;
    }


} catch (\Exception $e) {
    $errorMessage = $e->getMessage();
    if (!empty($errorMessage)) {
        $result["errorMessage"] = $errorMessage;
    }
    $result["type"] = "error";
}

echo json_encode($result);
