<?php

declare(strict_types=1);

namespace Koality\MagentoPlugin\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    public const API_KEY = 'koality/api/key';

    public const RUSH_HOUR_BEGIN = 'koality/rush_hour/begin';

    public const RUSH_HOUR_END = 'koality/rush_hour/end';

    public const RUSH_HOUR_INCLUDED_WEEKEND = 'koality/rush_hour/include_weekends';

    public const MIN_ORDERS_PER_HOUR = 'koality/orders_per_hour/min_orders_per_normal_hour';

    public const MIN_ORDERS_PER_RUSH_HOUR = 'koality/orders_per_hour/min_orders_per_rush_hour';

    public const MAX_OPEN_CARTS_PER_NORMAL_HOUR = 'koality/open_carts/max_open_carts_per_normal_hour';

    public const MAX_OPEN_CARTS_PER_RUSH_HOUR = 'koality/open_carts/max_open_carts_per_rush_hour';

    public const ACTIVE_PRODUCTS = 'koality/active_products/min_active_products';

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
        return (string)$this->scopeConfig->getValue(self::API_KEY, ScopeInterface::SCOPE_STORE);
    }

    public function getRushHourBegin(): ?string
    {
        return (string)$this->scopeConfig->getValue(self::RUSH_HOUR_BEGIN, ScopeInterface::SCOPE_STORE);
    }

    public function getRushHourEnd(): ?string
    {
        return (string)$this->scopeConfig->getValue(self::RUSH_HOUR_END, ScopeInterface::SCOPE_STORE);
    }

    public function getMinOrdersPerRushHour(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::MIN_ORDERS_PER_RUSH_HOUR, ScopeInterface::SCOPE_STORE);
    }

    public function doesRushHourHappenWeekends(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::RUSH_HOUR_INCLUDED_WEEKEND, ScopeInterface::SCOPE_STORE);
    }

    public function getMinOrdersPerHourNormal(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::MIN_ORDERS_PER_HOUR, ScopeInterface::SCOPE_STORE);
    }

    public function getMaxOpenCartsPerNormalHour(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::MAX_OPEN_CARTS_PER_NORMAL_HOUR, ScopeInterface::SCOPE_STORE);
    }

    public function getMaxOpenCartsPerNormalRushHour(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::MAX_OPEN_CARTS_PER_RUSH_HOUR, ScopeInterface::SCOPE_STORE);
    }

    public function getActiveProducts(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::ACTIVE_PRODUCTS, ScopeInterface::SCOPE_STORE);
    }
}
