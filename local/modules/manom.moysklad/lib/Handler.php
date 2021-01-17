<?php

namespace Manom\Moysklad;


use Manom\Moysklad\Moysklad\CustomerOrder;

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

                if ($this->isOrderCreateEvent($event)) {
                    $result = (new CustomerOrder($event->meta->href))->resetReserves();
                } elseif ($this->isOrderChangeEvent($event)) {
                    $result = EventTable::add(["href_change" => $event->meta->href]);
                } else {
                    $result = true;
                }
            }

            if ($result) {
                header("HTTP/1.0 200 OK");
            }
        } catch (\Exception $e) {
        }
    }

    public function isOrderCreateEvent($event): bool
    {
        return $event->meta->type === "customerorder" && $event->action === "CREATE";
    }

    public function isOrderChangeEvent($event): bool
    {
        return $event->meta->type === "customerorder" && $event->action === "UPDATE";
    }
}