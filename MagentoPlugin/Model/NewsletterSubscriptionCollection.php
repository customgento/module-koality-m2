<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Model\Formatter\Result;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;

class NewsletterSubscriptionCollection
{
    /**
     * @var array
     */
    private $pluginConfig;

    /**
     * @var CollectionFactory
     */
    private $subscriberCollectionFactory;

    public function __construct(array $pluginConfig, CollectionFactory $subscriberCollectionFactory)
    {
        $this->subscriberCollectionFactory = $subscriberCollectionFactory;
        $this->pluginConfig                = $pluginConfig;
    }

    public function getResult(): Result
    {
        $newsletterSubscriptions = $this->getNewsletterRegistrations();

        if (array_key_exists('newsletterSubscriptions', $this->pluginConfig)) {
            $minNewsletterSubscriptions = $this->pluginConfig['newsletterSubscriptions'];
        } else {
            $minNewsletterSubscriptions = 0;
        }

        if ($newsletterSubscriptions < $minNewsletterSubscriptions) {
            $newsletterResult = new Result(Result::STATUS_FAIL, Result::KEY_NEWSLETTER_TOO_FEW,
                'There were too few newsletter subscriptions yesterday.');
        } else {
            $newsletterResult = new Result(Result::STATUS_PASS, Result::KEY_NEWSLETTER_TOO_FEW,
                'There were enough newsletter subscriptions yesterday.');
        }

        $newsletterResult->setLimit($minNewsletterSubscriptions);
        $newsletterResult->setObservedValue($newsletterSubscriptions);
        $newsletterResult->setObservedValueUnit('newsletters');
        $newsletterResult->setLimitType(Result::LIMIT_TYPE_MIN);
        $newsletterResult->setType(Result::TYPE_TIME_SERIES_NUMERIC);

        return $newsletterResult;
    }

    private function getNewsletterRegistrations(): int
    {
        $toTime               = date("Y-m-d H:i:s");
        $fromTime             = date('Y-m-d H:i:s', strtotime('-1 hour'));
        $subscriberCollection = $this->subscriberCollectionFactory->create()
            ->addFieldToFilter('created_at',
                [
                    'from' => $fromTime,
                    'to'   => $toTime
                ]);
        $subscriberSize       = $subscriberCollection->getSize();
        if ($subscriberSize <= 0) {
            return -1;
        }

        return $subscriberSize;

    }
}
