<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Model\Formatter\Result;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\ResourceModel\Quote\CollectionFactory as QuoteCollectionFactory;

class OpenCartsCollector
{
    /**
     * @var array
     */
    private $pluginConfig;

    /**
     * @var QuoteCollectionFactory
     */
    private $quoteCollectionFactory;

    public function __construct(array $pluginConfig, QuoteCollectionFactory $quoteCollectionFactory)
    {
        $this->quoteCollectionFactory = $quoteCollectionFactory;
        $this->pluginConfig           = $pluginConfig;
    }

    public function getResult(): Result
    {
        $cartCount    = $this->getOpenCartCountFromLastHour();
        $maxCartCount = $this->pluginConfig['openCarts'];

        if ($cartCount > $maxCartCount) {
            $cartResult = new Result(Result::STATUS_FAIL, Result::KEY_CARTS_OPEN_TOO_MANY,
                'There are too many open carts at the moment.');
        } else {
            $cartResult = new Result(Result::STATUS_PASS, Result::KEY_CARTS_OPEN_TOO_MANY,
                'There are not too many open carts at the moment.');
        }
        $cartResult->setLimit($maxCartCount);
        $cartResult->setObservedValue($cartCount);
        $cartResult->setObservedValueUnit('carts');
        $cartResult->setObservedValuePrecision(0);
        $cartResult->setLimitType(Result::LIMIT_TYPE_MAX);
        $cartResult->setType(Result::TYPE_TIME_SERIES_NUMERIC);

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
