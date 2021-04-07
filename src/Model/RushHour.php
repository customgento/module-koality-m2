<?php

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Model\Config;
use Magento\Framework\Stdlib\DateTime\DateTime;

class RushHour
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var DateTime
     */
    private $date;

    public function __construct(Config $config, DateTime $date)
    {
        $this->config = $config;
        $this->date   = $date;
    }

    public function isRushHour(): bool
    {
        $timeStamp              = $this->date->gmtTimestamp();
        $beginRushHourTimeArray = explode(',', $this->config->getRushHourBegin());
        $beginRushHourTimestamp = strtotime($beginRushHourTimeArray[0] . ':' . $beginRushHourTimeArray[1] . ':'
            . $beginRushHourTimeArray[2]);
        $endRushHourTimeArray   = explode(',', $this->config->getRushHourEnd());
        $endRushHourTimestamp   = strtotime($endRushHourTimeArray[0] . ':' . $endRushHourTimeArray[1] . ':'
            . $endRushHourTimeArray[2]);

        return $timeStamp > $beginRushHourTimestamp && $timeStamp < $endRushHourTimestamp;
    }

    private function isWeekend(): bool
    {
        $currentWeekDay = date('w');

        return ($currentWeekDay === 0 || $currentWeekDay === 6);
    }

    public function shouldAllowRushHour(): bool
    {
        return !($this->isWeekend() && !$this->config->doesRushHourHappenWeekends());
    }
}
