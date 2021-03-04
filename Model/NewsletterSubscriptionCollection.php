<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Koality\MagentoPlugin\Api\ResultInterface;
use Koality\MagentoPlugin\Model\Formatter\Result;
use Koality\MagentoPlugin\Model\Config;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory;

class NewsletterSubscriptionCollection
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var ResultInterface
     */
    private $resultInterface;

    /**
     * @var CollectionFactory
     */
    private $subscriberCollectionFactory;

    /**
     * @var Config
     */
    private $config;

    public function __construct(
        CollectionFactory $subscriberCollectionFactory,
        ResultInterface $resultInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Config $config
    ) {
        $this->subscriberCollectionFactory = $subscriberCollectionFactory;
        $this->resultInterface             = $resultInterface;
        $this->searchCriteriaBuilder       = $searchCriteriaBuilder;
        $this->config                      = $config;
    }

    public function getResult(): ResultInterface
    {
        $newsletterSubscriptions = $this->getNewsletterRegistrations();

        if ($this->config->getNewsletterSubscribers()) {
            $minNewsletterSubscriptions = $this->config->getNewsletterSubscribers();
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

        $newsletterResult->setLimit($minNewsletterSubscriptions);
        $newsletterResult->setObservedValue($newsletterSubscriptions);
        $newsletterResult->setObservedValuePrecision(0);
        $newsletterResult->setObservedValueUnit('newsletters');
        $newsletterResult->setLimitType(ResultInterface::LIMIT_TYPE_MIN);
        $newsletterResult->setType(ResultInterface::TYPE_TIME_SERIES_NUMERIC);

        return $newsletterResult;
    }

    private function getNewsletterRegistrations(): int
    {
        $orderTo   = date("Y-m-d H:i:s");
        $orderFrom = date('Y-m-d H:i:s', strtotime('-1 days'));
        //We use a collection here because an interface for newsletters does not exist
        $subscriberCollection = $this->subscriberCollectionFactory->create()
            ->addFieldToSelect('*')
            ->addFieldToSelect('created_at', $orderFrom)
            ->addFieldToSelect('created_at', $orderTo);
        $subscriberSize       = $subscriberCollection->getSize();
        if ($subscriberSize <= 0) {
            return -1;
        }

        return $subscriberSize;

    }
}
