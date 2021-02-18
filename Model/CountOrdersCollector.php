<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Api\ResultInterface;
use Koality\MagentoPlugin\Model\Formatter\Result;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class CountOrdersCollector
{
    /**
     * @var ResultInterface
     */
    private $resultInterface;

    private array $pluginConfig = [];

    private CollectionFactory $orderCollectionFactory;

    public function __construct(CollectionFactory $orderCollectionFactory, ResultInterface $resultInterface)
    {
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->resultInterface        = $resultInterface;
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
        $config = $this->pluginConfig;

        $currentWeekDay = date('w');
        $isWeekend      = ($currentWeekDay === 0 || $currentWeekDay === 6);

        $allowRushHour = !($isWeekend && !$config['includeWeekends']);

        if ($allowRushHour && array_key_exists('rushHourBegin', $config) && array_key_exists('rushHourEnd', $config)) {
            $beginHour = (int)substr($config['rushHourBegin'], 0, 2) . substr($config['rushHourBegin'], 3, 2);
            $endHour   = (int)substr($config['rushHourEnd'], 0, 2) . substr($config['rushHourEnd'], 3, 2);

            $currentTime = (int)date('Hi');

            if ($currentTime < $endHour && $currentTime > $beginHour) {
                //TODO check this
                return $config['ordersPerHourRushHour'];
            }
        }

        //TODO check this
        return $config['ordersPerHourNormal'];
    }

    /**
     * Get the number of orders within the last hour.
     *
     * @return int
     */
    private function getLastHourOrderCount(): int
    {
        $toTime   = date("Y-m-d H:i:s");
        $fromTime = date('Y-m-d H:i:s', strtotime('- 1 hour'));
        $orders   = $this->orderCollectionFactory->create()
            ->addAttributeToFilter('created_at', ['from' => $fromTime, 'to' => $toTime]);

        return $orders->getTotalCount();
    }
}
