<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Api\ResultInterface;
use Koality\MagentoPlugin\Model\Formatter\Result;
use Koality\MagentoPlugin\Model\Config;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;

class OpenCartsCollector
{
    /**
     * @var ResultInterface
     */
    private $resultInterface;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var QuoteCollectionFactory
     */
    private $quoteCollectionFactory;

    public function __construct(
        QuoteCollectionFactory $quoteCollectionFactory,
        ResultInterface $resultInterface,
        Config $config
    ) {
        $this->quoteCollectionFactory = $quoteCollectionFactory;
        $this->resultInterface        = $resultInterface;
        $this->config                 = $config;
    }

    public function getResult(): Result
    {
        $cartCount    = $this->getOpenCartCountFromLastHour();
        $maxCartCount = $this->config->getOpenCarts();

        if ($cartCount > $maxCartCount) {
            $cartResult = new Result(ResultInterface::STATUS_FAIL, ResultInterface::KEY_CARTS_OPEN_TOO_MANY,
                'There are too many open carts at the moment.');
        } else {
            $cartResult = new Result(ResultInterface::STATUS_PASS, ResultInterface::KEY_CARTS_OPEN_TOO_MANY,
                'There are not too many open carts at the moment.');
        }
        $this->resultInterface->setLimit($maxCartCount);
        $this->resultInterface->setObservedValue($cartCount);
        $this->resultInterface->setObservedValueUnit('carts');
        $this->resultInterface->setObservedValuePrecision(0);
        $this->resultInterface->setLimitType(ResultInterface::LIMIT_TYPE_MAX);
        $this->resultInterface->setType(ResultInterface::TYPE_TIME_SERIES_NUMERIC);

        return $cartResult;
    }

    private function getOpenCartCountFromLastHour(): int
    {
        $toTime          = date("Y-m-d H:i:s");
        $fromTime        = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $quoteCollection = $this->quoteCollectionFactory->create()
            ->addFieldToFilter(CartInterface::KEY_IS_ACTIVE, ['eq' => 1])
            ->addFieldToFilter('created_at', ['from' => $fromTime, 'to' => $toTime]);

        return $quoteCollection->getSize();

    }
}
