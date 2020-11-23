<?php

namespace Manom;


use Bitrix\Main\Loader;
use Manom\Nextjs\Api\Store;

class WeekTools
{
    public $currentDay = 0;
    public $currentHour = 0;
    public $currentTimeString = "";
    public $days = [
        'вс',
        'пн',
        'вт',
        'ср',
        'чт',
        'пт',
        'сб',
    ];

    public function __construct()
    {
        $this->currentDay = (int)date('w');
        $this->currentHour = (int)date('G');
        $this->currentTimeString = date("G:i");
    }

    public function calcDiffDay($startDay, $endDay): int
    {
        $sumDays = 0;
        $roundNum = 0;

        if ($endDay > count($this->days)) {
            return $sumDays;
        }

        $i = $startDay;
        while ($i < count($this->days)) {
            if ($roundNum <= 0) {
                $roundNum = 1;
            }
            if ($i > $startDay || $roundNum > 1) {
                $sumDays++;
            }

            if ($i === $endDay) {
                break;
            }
            if ($i === (count($this->days) - 1)) {
                $i = 0;
                $roundNum++;
            } else {
                $i++;
            }
        }
        return $sumDays;
    }

    public function getTextPeriod($deliveryObj): string
    {
        $textNearestDate = '';
        if ($deliveryObj['isSdek']) {
            $newCurPeriod = '';
            for ($i = 0, $iMax = strlen($deliveryObj["currentPeriod"]); $i < $iMax; $i++) {
                if ((int)$deliveryObj['currentPeriod'][$i] > 0 || $deliveryObj['currentPeriod'][$i] === '-') {
                    $newCurPeriod .= $deliveryObj['currentPeriod'][$i];
                }
            }
            $deliveryObj['currentPeriod'] = $newCurPeriod;

            $period = explode('-', $deliveryObj['currentPeriod']);
            if (count($period) <= 0) {
                return '';
            }

            $periodStart = array_shift($period);
            $periodEnd = array_shift($period);
            $offset = 0;

            if ($periodStart) {
                if (
                    $this->currentDay >= (int)$deliveryObj['dates']['start'] &&
                    $this->currentDay <= $deliveryObj['dates']['end']
                ) {
                    $offset = 0;
                } else {
                    $offset = $this->calcDiffDay($this->currentDay, $deliveryObj['dates']['start']);
                }

                $textNearestDate = (string)($offset + (int)$periodStart);
            }

            if ($periodEnd) {
                $textNearestDate .= '-' . ($offset + (int)$periodEnd);
            }

            if ($textNearestDate !== '') {
                $textNearestDate .= ' дня';
            }

            return $textNearestDate;
        }

        if (!$deliveryObj['exist']) {
            if ($this->currentHour < $deliveryObj['time']['end']) {
                $textNearestDate = 'Сегодня';
            } else {
                $textNearestDate = 'Завтра';
            }

            return $textNearestDate;
        }

        if (
            $this->currentDay >= $deliveryObj['dates']['start'] &&
            $this->currentDay <= $deliveryObj['dates']['end']
        ) {
            if ($this->currentHour < $deliveryObj['time']['end'] - 1) {
                $textNearestDate = $deliveryObj['exist'] ?
                    'Сегодня до ' . $deliveryObj['time']['end'] . ':00' :
                    'Сегодня';
            } elseif ($this->currentDay === $deliveryObj['dates']['end']) {
                $textNearestDate = 'Через 2 дня';
            } else {
                $textNearestDate = 'Завтра';
            }
        } else {
            $dayOffset = $this->calcDiffDay($this->currentDay, $deliveryObj['dates']['start']);

            switch ($dayOffset) {
                case 0:
                    $textNearestDate = 'Сегодня';
                    break;
                case 1:
                    $textNearestDate = 'Завтра';
                    break;
                case 2:
                    $textNearestDate = 'Послезавтра';
                    break;
                default:
                    $textNearestDate = 'Через ' . $dayOffset . ' дня';
                    break;
            }
        }

        return $textNearestDate;
    }

    /**
     * @param $schedule
     * @return array
     */
    public function parseScheduleShop($schedule)
    {
        $scheduleData = [];

        $schedule = explode(' ', $schedule);
        $schedule = is_array($schedule) ? $schedule : [];
        $time = array_pop($schedule);
        $time = explode('-', $time);
        $scheduleData['fullTimeStart'] = array_shift($time);
        $scheduleData['fullTimeEnd'] = array_shift($time);
        $scheduleData['hourStart'] = (int)$scheduleData['fullTimeStart'];
        $scheduleData['hourEnd'] = (int)$scheduleData['fullTimeEnd'];
        $days = array_pop($schedule);
        $days = explode('-', $days);
        $scheduleData['dayStart'] = array_search(array_shift($days), $this->days, true);
        $scheduleData['dayEnd'] = array_search(array_shift($days), $this->days, true);


        $scheduleData['isOpen'] = ($this->currentDay >= $scheduleData['dayStart']
                && $this->currentDay <= $scheduleData['dayEnd'])
            && (strtotime($this->currentTimeString) >= strtotime($scheduleData['fullTimeStart'])
                && strtotime($this->currentTimeString) <= strtotime($scheduleData['fullTimeEnd']));

        return $scheduleData;
    }

    /**
     * @return string
     */
    public function getDateCacheCleaner()
    {
        $cleaner = "$this->currentHour;$this->currentDay;";
        try {
            if (!Loader::includeModule("manom.nextjs")) {
                return $cleaner;
            }

            $mainStore = (new Store())->getMain();
            $cleaner .= "{$mainStore["schedule"]};";
        } catch (\Exception $e) {
        }

        return $cleaner;
    }
}