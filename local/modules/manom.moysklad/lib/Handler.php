<?php

namespace Manom\Moysklad;


use Manom\Tools;

class Handler
{
    public function __construct()
    {
    }

    public function process()
    {
        $requestData = file_get_contents('php://input');
        $requestData = json_decode($requestData);
        $result = false;
        if (empty($requestData)) {
            die();
        }

        $events = $requestData->events;
        if (!$events) {
            $events = [];
        }

        try {
            foreach ($events as $event) {
                if (empty($event->meta)) {
                    continue;
                }

                $event = [
                    "href_entity" => $event->meta->href,
                    "entity"      => $event->meta->type,
                    "type"        => $event->action,
                ];


                $result = EventTable::add($event);
            }

            if ($result) {
                header("HTTP/1.0 200 OK");
            }
        } catch (\Exception $e) {
            Tools::errorToLog($e, "ms_handler", file_get_contents('php://input'));
        }
    }
}