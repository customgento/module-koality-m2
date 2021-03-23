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

    public const ORDERS_PER_RUSH_HOUR = 'koality/rush_hour/orders_per_rush_hour';

    public const RUSH_HOUR_INCLUDED_WEEKEND = 'koality/rush_hour/include_weekends';

    public const ORDERS_PER_HOUR_NORMAL = 'koality/opening_hours/orders_per_hour_normal';

    public const OPEN_CARTS_EXISTS = 'koality/open_carts/max_number';

    public const ACTIVE_PRODUCTS = 'koality/active_products/min_number';

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

    public function getOrdersPerRushHour(): ?int
    {
        return (int)$this->scopeConfig->getValue(self::ORDERS_PER_RUSH_HOUR, ScopeInterface::SCOPE_STORE);
    }

    public function doesRushHourHappenWeekends(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::RUSH_HOUR_INCLUDED_WEEKEND, ScopeInterface::SCOPE_STORE);
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
}
