<?php
/**
 * Created by PhpStorm.
 * User: Вячеслав
 * Date: 10.04.2019
 * Time: 12:37
 */

namespace Roistat;

class RoistatSender
{

    public function __construct()
    {
    }

    /**
     * @param array $data
     */
    public static function processCart($data){
        if (!$data['phone']){
            return;
        }

        if (!$data['name']){
            $data['name'] = 'Неизвестный контакт';
        }

        $roistatData = array(
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'form'=>'Корзина',
            'type'=>'Корзина',
            'email'=> $data['email'],
            'comment'=> $data['text'],
            'title'=>$data['title'],
        );

        self::sendData($roistatData);
    }

    /**
     * @param array $data
     */
    public static function processCallback($data){
        if (!$data['phone']){
            return;
        }

        if (!$data['name']){
            $data['name'] = 'Неизвестный контакт';
        }

        $roistatData = array(
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'form'=>'Заказать звонок',
            'type'=>'Заявка с сайта',
            'email'=> '',
            'comment'=> '',
            'title'=>"Заявка с 'Заказать звонок'",
        );

        self::sendData($roistatData);
    }

    /**
     * @param array $data
     */
    public static function processQuickOrder($data){
        if (!$data['phone']){
            return;
        }

        if (!$data['name']){
            $data['name'] = 'Неизвестный контакт';
        }

        $roistatData = array(
            'name'=>$data['name'],
            'phone'=>$data['phone'],
            'form'=>'Купить в 1 клик',
            'type'=>'Заявка с сайта',
            'email'=> $data['email'],
            'comment'=> $data['text'],
            'title'=>"Заявка с 'Купить в 1 клик'",
        );

        self::sendData($roistatData);
    }


    /*
     * Send accumulated data to Roistat
     *
     * @param array $data array with data
     *
     * @return boolean
     * */
    private static function sendData($data){

        $visit = "no_cookie";
        if (isset($_COOKIE['roistat_visit'])){
            $visit = $_COOKIE['roistat_visit'];
        }

        $roistatData = array(
            'roistat' => $visit,
            'key'     => Config::ROISTAT_INTEGRATION_KEY, // Ключ для интеграции с CRM, указывается в настройках интеграции с CRM.
            'title'   => isset($data['title'])?$data['title']:"Заявка с {$data['form']}", // Название сделки
            'comment' => $data['comment'], // Комментарий к сделке
            'name'    => $data['name'], // Имя клиента
            'email'   => $data['email'], // Email клиента
            'phone'   => $data['phone'], // Номер телефона клиента
            'fields'  => array(
                'cf85082f2-f4b8-11e9-0a80-0667002ace2f' => $data['type'], // Тип обращения
            ),
        );

        file_get_contents("https://cloud.roistat.com/api/proxy/1.0/leads/add?" . http_build_query($roistatData));

        return true;
    }

    /*
     * Send debug string
     *
     * @param string $debug_str debug string
     *
     * */
    public static function sendDebug($debug_str){

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::DEBUG_URL);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $debug_str);
        curl_exec($curl);
        curl_close($curl);

    }

    /*
     * Set string to log
     *
     * @param string $debug_str debug string
     *
     * */
    public static function log($debug_str){

        file_put_contents("log.log",$debug_str.PHP_EOL, FILE_APPEND);

    }
}