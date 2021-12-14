<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Api\CollectorInterface;
use Koality\MagentoPlugin\Api\ResultInterface;
use Koality\MagentoPlugin\Model\Config;
use Koality\MagentoPlugin\Model\Formatter\Result;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Koality\MagentoPlugin\Model\RushHour;

class CountOrdersCollector implements CollectorInterface
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
     * @var RushHour
     */
    private $rushHour;

    /**
     * @var Config
     */
    private $config;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepositoryInterface $orderRepository,
        Config $config,
        RushHour $rushHour
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository       = $orderRepository;
        $this->config                = $config;
        $this->rushHour              = $rushHour;
    }

    public function getResult(): ResultInterface
    {
        $salesThreshold     = $this->getCurrentSalesThreshold();
        $currentOrdersCount = $this->getLastHourOrderCount();

        if ($currentOrdersCount < $salesThreshold) {
            $orderResult = new Result(
                ResultInterface::STATUS_FAIL,
                ResultInterface::KEY_ORDERS_TOO_FEW,
                'There were too few orders within the last hour.'
            );
        } else {
            $orderResult = new Result(
                ResultInterface::STATUS_PASS,
                ResultInterface::KEY_ORDERS_TOO_FEW,
                'There were enough orders within the last hour.'
            );
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
        if ($this->rushHour->isRushHour()) {
            return (int)$this->config->getMinOrdersPerRushHour();
        }

        return (int)$this->config->getMinOrdersPerHourNormal();
    }

    /**
     * Get the number of orders within the last hour.
     *
     * @return int
     */
    private function getLastHourOrderCount(): int
    {
        $orderTo        = date('Y-m-d H:i:s');
        $orderFrom      = date('Y-m-d H:i:s', strtotime('- 1 hour'));
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(CartInterface::KEY_CREATED_AT, $orderFrom, 'gteq')
            ->addFilter(CartInterface::KEY_CREATED_AT, $orderTo, 'lteq')->create();

        return $this->orderRepository->getList($searchCriteria)->getTotalCount();
    }
}
