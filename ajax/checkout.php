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
    }


} catch (\Exception $e) {
    $result["type"] = "error";
}

echo json_encode($result);
