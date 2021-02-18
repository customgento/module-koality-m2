<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Api\ResultInterface;
use Koality\MagentoPlugin\Model\Config;
use Koality\MagentoPlugin\Model\Formatter\Result;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\OrderRepositoryInterface;

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
     * @var ResultInterface
     */
    private $resultInterface;

    /**
     * @var Config
     */
    private $config;

    public function __construct(
        ResultInterface $resultInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepositoryInterface $orderRepository,
        Config $config
    ) {
        $this->resultInterface       = $resultInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository       = $orderRepository;
        $this->config                = $config;
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

        $this->resultInterface->setLimit($salesThreshold);
        $this->resultInterface->setObservedValue($currentOrdersCount);
        $this->resultInterface->setObservedValuePrecision(2);
        $this->resultInterface->setObservedValueUnit('orders');
        $this->resultInterface->setLimitType(ResultInterface::LIMIT_TYPE_MIN);
        $this->resultInterface->setType(ResultInterface::TYPE_TIME_SERIES_NUMERIC);

        return $orderResult;
    }

    /**
     * Return the sales threshold depending on the current time.
     *
     * @return int
     */
    private function getCurrentSalesThreshold(): int
    {
        $currentWeekDay = date('w');
        $isWeekend      = ($currentWeekDay === 0 || $currentWeekDay === 6);

        $allowRushHour = !($isWeekend && !$this->config->doesRushHourHappenWeekends());

        if ($allowRushHour && $this->config->getRushHourBegin() && $this->config->getRushHourEnd()) {
            $beginHour   = $this->config->getRushHourBegin();
            $endHour     = $this->config->getRushHourEnd();
            $currentTime = (int)date('Hi');
            if ($currentTime < $endHour && $currentTime > $beginHour) {
                return $this->config->getOrdersPerRushHour();
            }
        }

        return $this->config->getOrdersPerHourNormal();
    }

    /**
     * Get the number of orders within the last hour.
     *
     * @return int
     */
    private function getLastHourOrderCount(): int
    {
        $toTime         = date("Y-m-d H:i:s");
        $fromTime       = date('Y-m-d H:i:s', strtotime('- 1 hour'));
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('created_at', ['from' => $fromTime, 'to' => $toTime])->create();

        return $this->orderRepository->getList($searchCriteria)->getTotalCount();
    }
}
