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
    }


} catch (\Exception $e) {
    $errorMessage = $e->getMessage();
    if (!empty($errorMessage)) {
        $result["errorMessage"] = $errorMessage;
    }
    $result["type"] = "error";
}

echo json_encode($result);
