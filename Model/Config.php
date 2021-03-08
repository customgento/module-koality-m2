<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    public const KOALITY_API_KEY = 'koality_module_magento/koality_apikey/apikey';

    public const RUSHHOUR_BEGIN = 'koality_module_magento/rush_hour/rushHourBegin';

    public const RUSHHOUR_END = 'koality_module_magento/rush_hour/rushHourEnd';

    public const ORDERS_PER_RUSHHOUR = 'koality_module_magento/rush_hour/ordersPerHourRushHour';

    public const RUSHHOUR_INCLUDED_WEEKEND = 'koality_module_magento/rush_hour/includeWeekends';

    public const ORDERS_PER_HOUR_NORMAL = 'koality_module_magento/opening_hours/ordersPerHourNormal';

    public const OPEN_CARTS_EXISTS = 'koality_module_magento/open_carts/openCarts';

    public const ACTIVE_PRODUCTS = 'koality_module_magento/active_products/activeProducts';

    public const NEWSLETTER_SUBSCRIBERS = 'koality_module_magento/newsletter_subscribers/newsletterSubscriptions';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getApiKey(): ?string
    {
        return (string)$this->scopeConfig->getValue(self::KOALITY_API_KEY, ScopeInterface::SCOPE_STORE);
    }

    public function getRushHourBegin(): ?string
    {
        return (string)$this->scopeConfig->getValue(self::RUSHHOUR_BEGIN, ScopeInterface::SCOPE_STORE);
    }

    public function getRushHourEnd(): ?string
    {
        return (string)$this->scopeConfig->getValue(self::RUSHHOUR_END, ScopeInterface::SCOPE_STORE);
    }

    public function getOrdersPerRushHour(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::ORDERS_PER_RUSHHOUR, ScopeInterface::SCOPE_STORE);
    }

    public function doesRushHourHappenWeekends(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::RUSHHOUR_INCLUDED_WEEKEND, ScopeInterface::SCOPE_STORE);
    }

    public function getOrdersPerHourNormal(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::ORDERS_PER_HOUR_NORMAL, ScopeInterface::SCOPE_STORE);
    }

    public function getOpenCarts(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::OPEN_CARTS_EXISTS, ScopeInterface::SCOPE_STORE);
    }

    public function getActiveProducts(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::ACTIVE_PRODUCTS, ScopeInterface::SCOPE_STORE);
    }

    public function getNewsletterSubscribers(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::NEWSLETTER_SUBSCRIBERS, ScopeInterface::SCOPE_STORE);
    }
}
