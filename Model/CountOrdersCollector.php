<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Api\ResultInterface;
use Koality\MagentoPlugin\Model\Config;
use Koality\MagentoPlugin\Model\Formatter\Result;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class CountOrdersCollector
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var DateTime
     */
    private $date;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepositoryInterface $orderRepository,
        Config $config,
        DateTime $date
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository       = $orderRepository;
        $this->config                = $config;
        $this->date                  = $date;
    }

    public function getResult(): ResultInterface
    {
        $salesThreshold     = $this->getCurrentSalesThreshold();
        $currentOrdersCount = $this->getLastHourOrderCount();

        if ($currentOrdersCount < $salesThreshold) {
            $orderResult = new Result(ResultInterface::STATUS_FAIL, ResultInterface::KEY_ORDERS_TOO_FEW,
                'There were too few orders within the last hour.');
        } else {
            $orderResult = new Result(ResultInterface::STATUS_PASS, ResultInterface::KEY_ORDERS_TOO_FEW,
                'There were enough orders within the last hour.');
        }

        $orderResult->setLimit($salesThreshold);
        $orderResult->setObservedValue($currentOrdersCount);
        $orderResult->setObservedValuePrecision(2);
        $orderResult->setObservedValueUnit('orders');
        $orderResult->setLimitType(ResultInterface::LIMIT_TYPE_MIN);
        $orderResult->setType(ResultInterface::TYPE_TIME_SERIES_NUMERIC);

        return $orderResult;
    }

    /**
     * Return the sales threshold depending on the current time.
     *
     * @return int
     */
    private function getCurrentSalesThreshold(): ?int
    {
        $currentWeekDay = date('w');
        $isWeekend      = ($currentWeekDay === 0 || $currentWeekDay === 6);
        $allowRushHour  = !($isWeekend && !$this->config->doesRushHourHappenWeekends());
        if ($allowRushHour &&  $this->isRushHour()) {
            return (int)$this->config->getOrdersPerRushHour();
        }

        return (int)$this->config->getOrdersPerHourNormal();
    }

    /**
     * Get the number of orders within the last hour.
     *
     * @return int
     */
    private function getLastHourOrderCount(): int
    {
        $orderTo        = date("Y-m-d H:i:s");
        $orderFrom      = date('Y-m-d H:i:s', strtotime('- 1 hour'));
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('created_at', $orderFrom, 'gteq')
            ->addFilter('created_at', $orderTo, 'lteq')->create();

        return $this->orderRepository->getList($searchCriteria)->getTotalCount();
    }

    private function isRushHour(): bool
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
}
