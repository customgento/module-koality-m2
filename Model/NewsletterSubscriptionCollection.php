<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Api\ResultInterface;
use Koality\MagentoPlugin\Model\Formatter\Result;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;

class NewsletterSubscriptionCollection
{
    /**
     * @var ResultInterface
     */
    private $resultInterface;

    /**
     * @var array
     */
    private $pluginConfig;

    /**
     * @var CollectionFactory
     */
    private $subscriberCollectionFactory;

    public function __construct(
        array $pluginConfig,
        CollectionFactory $subscriberCollectionFactory,
        ResultInterface $resultInterface
    ) {
        $this->subscriberCollectionFactory = $subscriberCollectionFactory;
        $this->pluginConfig                = $pluginConfig;
        $this->resultInterface             = $resultInterface;
    }

    public function getResult(): ResultInterface
    {
        $newsletterSubscriptions = $this->getNewsletterRegistrations();

        if (array_key_exists('newsletterSubscriptions', $this->pluginConfig)) {
            $minNewsletterSubscriptions = $this->pluginConfig['newsletterSubscriptions'];
        } else {
            $minNewsletterSubscriptions = 0;
        }

        if ($newsletterSubscriptions < $minNewsletterSubscriptions) {
            $newsletterResult = new Result(ResultInterface::STATUS_FAIL, ResultInterface::KEY_NEWSLETTER_TOO_FEW,
                'There were too few newsletter subscriptions yesterday.');
        } else {
            $newsletterResult = new Result(ResultInterface::STATUS_PASS, ResultInterface::KEY_NEWSLETTER_TOO_FEW,
                'There were enough newsletter subscriptions yesterday.');
        }

        $this->resultInterface->setLimit($minNewsletterSubscriptions);
        $this->resultInterface->setObservedValue($newsletterSubscriptions);
        $this->resultInterface->setObservedValueUnit('newsletters');
        $this->resultInterface->setLimitType(ResultInterface::LIMIT_TYPE_MIN);
        $this->resultInterface->setType(ResultInterface::TYPE_TIME_SERIES_NUMERIC);

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
