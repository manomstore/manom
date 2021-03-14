<?php

namespace Manom;

use Manom\Store\StoreList;

class WeekTools
{
    public $currentDay = 0;
    public $currentHour = 0;
    public $currentTimeString = "";
    public $assemblyTime = 0;
    public $days = [
        'вс',
        'пн',
        'вт',
        'ср',
        'чт',
        'пт',
        'сб',
    ];

    /**
     * WeekTools constructor.
     * @param int $productId
     */
    public function __construct($productId = 0)
    {
        $this->currentDay = (int)date('w');
        $this->currentHour = (int)date('G');
        $this->currentTimeString = date("G:i");
        $this->assemblyTime = Basket::getAssemblyTimeData($productId);
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
        $existRestDay = !(
            array_search("пн", $this->days) === $deliveryObj["dates"]["start"]
            && array_search("вс", $this->days) === $deliveryObj["dates"]["end"]
        );
        //fix for sunday
        $start = $deliveryObj['dates']['start'] === 0 ? 7 : $deliveryObj['dates']['start'];
        $end = $deliveryObj['dates']['end'] === 0 ? 7 : $deliveryObj['dates']['end'];
        $now = $this->currentDay === 0 ? 7 : $this->currentDay;
        //

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
            $cdekOffset = 0;

            if (!($now >= $start && $now <= $end) && $existRestDay) {
                $cdekOffset = $this->calcDiffDay($this->currentDay, $deliveryObj['dates']['start']);
            }

            $cdekOffset += $this->assemblyTime;

            if ($periodStart) {
                $periodStart = $cdekOffset + (int)$periodStart;
                $textNearestDate = (string)$periodStart;
            }

            if ($periodEnd) {
                $periodEnd = $cdekOffset + (int)$periodEnd;
                $textNearestDate .= '-' . (string)$periodEnd;
            }

            if ($textNearestDate !== '') {
                $textNearestDate .= " " . Content::getNumEnding($periodEnd, ['день', 'дня', 'дней']);
            }

            return $textNearestDate;
        }

        $dayOffset = 0;

        if ($deliveryObj['exist']) {
            $workingHour = $this->currentHour < $deliveryObj['time']['end'] - 1;

            $lastWorkDay = $this->currentDay === $deliveryObj['dates']['end'];

            if (!($now >= $start && $now <= $end) && $existRestDay) {
                if ($workingHour || !$lastWorkDay) {
                    $dayOffset = $this->calcDiffDay($this->currentDay, $deliveryObj['dates']['start']);
                }
            }
        } else {
            $workingHour = $this->currentHour < $deliveryObj['time']['end'];
        }

        if ($this->assemblyTime > 0) {
            //Добавляем текущий день к сроку сборки, если сейчас больше 9 утра
            if ($this->currentHour >= 9) {
                $this->assemblyTime++;
            }
        }

        $dayOffset += $this->assemblyTime;

        if ($dayOffset === 0 && !$workingHour) {
            $dayOffset++;
        }

        switch ($dayOffset) {
            case 0:
                if ($deliveryObj['exist']) {
                    $textNearestDate = 'Сегодня до ' . $deliveryObj['time']['end'] . ':00';
                } else {
                    $textNearestDate = 'Сегодня';
                }
                break;
            case 1:
                $textNearestDate = 'Завтра';
                break;
            case 2:
                $textNearestDate = 'Послезавтра';
                break;
            default:
                $textNearestDate = 'Через ' . $dayOffset . " " . Content::getNumEnding($dayOffset, [
                        'день',
                        'дня',
                        'дней'
                    ]);
                break;
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
            $schedule = StoreList::getInstance()->getShop()->getSchedule();
            $cleaner .= "{$schedule};";
        } catch (\Exception $e) {
        }

        return $cleaner;
    }
}