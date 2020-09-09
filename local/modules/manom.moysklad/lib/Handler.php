<?php

namespace Manom\Moysklad;


class Handler
{
    public function __construct()
    {
    }

    public function process()
    {
        $requestData = file_get_contents('php://input');
        $requestData = json_decode($requestData);
        if (empty($requestData)) {
            die();
        }

        $events = $requestData->events;
        if (!$events) {
            $events = [];
        }

        foreach ($events as $event) {
            if (empty($event->meta)) {
                continue;
            }

            EventTable::add(["href_change" => $event->meta->href]);
        }

        header("HTTP/1.0 200 OK");
    }
}